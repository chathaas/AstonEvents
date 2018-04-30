<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Event;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Redirect;
use Storage;
use URL;
use auth;
use File;

//session stores what events the filter will be applied to (key = filter_type)
//either all events (value = all) or the organisers events (value = organisers)
//filter_type is only stored using flash and reflash so filter_type is only stored when on filter page

//session stores what type of action the new_event page will carry out when the form is submitted (key = event_type)
//either new event (value = new) or modify existing event (value = modify)
//event_type is only stored using flash and reflash so filter_type is only stored when on filter page

class EventController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'listOrganisersEvents', 'getModifyPage', 'modify', 'delete', ]]);
    }

    public function create()
    {
        session()->flash('event_type', 'new');
        return view('new_event');
    }

    public function validateEvent(Request $request)
    {
        //unix 32-bit ends on 19/01/2038
        $endDate = "01/01/2038";

        $rules = [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Sport,Culture,Other',
            'date_time' => 'required|after_or_equal:now|before:'.$endDate,
            'description' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
        $messages = [
            'name.required' => 'The event name field is required.',

            'category.in' => 'Please select one of the categories.',

            'date_time.*' => 'The date and time must be after or equal to the current date and time & be before '.$endDate.' .',

            'image.required' => 'Please choose a suitable image.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The max file size is 2048KB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }
        return 'fails';
    }

    //====================STORE NEW EVENT====================

    public function store(Request $request)
    {
        $validator = $this->validateEvent($request);
        if($validator != 'fails') {
            return redirect('event/create')->withErrors($validator)->withInput();
        }

        $image = $request->file('image');

        $event = Event::create([
            'name' => $request['name'],
            'category' => $request['category'],
            'date_time' => Carbon::parse($request->input('date_time')),
            'description' => $request['description'],
            'place' => $request['place'],
            'image_file_path' => $image->getClientOriginalName(),
            'organiser_id' => auth::id(),
        ]);

        //store image in sub folder where event_id = directory name
        Storage::makeDirectory('public/images/'.$event->id);
        $image->storeAs('images/'.$event->id, $image->getClientOriginalName(), 'public');

        return redirect('home')->with('message', 'Event Created');
    }

    //====================GET SINGLE EVENT DATA====================

    public function getEventData($id)
    {
        //should only return one event however it is formatted as an array of events
        $events = DB::table('events')
        ->select('events.name AS event_name', 'events.description',
        'events.place', 'events.category', 'events.date_time',
        'events.likeness_ranking', 'events.image_file_path',
        'users.name AS user_name', 'users.id AS user_id',
        'users.phone_no AS user_phone_no')
        ->join('users', 'events.organiser_id', '=', 'users.id')
        ->where('events.id', '=', $id);
        $events = $events->get();
        $event = [];
        $event['event_id'] = $id;
        foreach($events as $selected_event) {
            $event['event_name'] = $selected_event->event_name;
            $event['description'] = $selected_event->description;
            $event['place'] = $selected_event->place;
            $event['category'] = $selected_event->category;
            $event['date_time'] = $selected_event->date_time;
            $event['user_name'] = $selected_event->user_name;
            $event['likeness_ranking'] = $selected_event->likeness_ranking;
            $event['image_file_path'] = $selected_event->image_file_path;
            $event['user_id'] = $selected_event->user_id;
            $event['user_phone_no'] = $selected_event->user_phone_no;
        }
        return $event;
    }

    //====================SHOW SINGLE EVENT====================

    public function show($id)
    {
        if(sizeOf($this->getEventData($id)) == 1) {
            abort(404);
        }

        return view('/show_event', array('event' => $this->getEventData($id)));
    }

    //====================LIST EVENTS====================

    public function listAll()
    {
        session()->flash('filter_type', 'all');

        $events = DB::table('events')
        ->select('events.name AS event_name', 'events.description',
        'events.date_time', 'events.id AS event_id', 'users.id AS user_id')
        ->join('users', 'events.organiser_id', '=', 'users.id');
        $events = $events->get();
        return view('/list_events', array('events'=>$events ));
    }

    //====================LIST CURRENT OGRANISERS EVENTS====================

    public function listOrganisersEvents() {
        session()->flash('filter_type', 'organisers');

        $events = DB::table('events')
        ->select('events.name AS event_name', 'events.description',
        'events.date_time', 'events.id AS event_id', 'users.id AS user_id')
        ->join('users', 'events.organiser_id', '=', 'users.id')
        ->where('events.organiser_id', '=', auth::id());
        $events = $events->get();
        return view('/list_events', array('events' => $events));
    }

    //====================FILTER EVENTS====================

    public function listByCategory($events, $category)
    {
        return $events
        ->where('category', '=', $category);
    }

    public function listByLikenessRanking($events, $likenessRanking)
    {
        return $events
        ->where('likeness_ranking', '>=', $likenessRanking);
    }

    //isBegin = boolean
    public function listByDate($events, $date, $isBegin)
    {
        if($isBegin)
        {
            return $events
            ->whereDate('date_time', '>=', $date);
        }
        return $events
        ->whereDate('date_time', '<=', $date);
    }

    public function filterEvents(Request $request)
    {
        //decide redirect page
        if(session()->get('filter_type') == 'all') {
            $events = DB::table('events')
            ->select('events.name AS event_name', 'events.description',
            'events.date_time', 'events.id AS event_id', 'users.id AS user_id')
            ->join('users', 'events.organiser_id', '=', 'users.id');
        } else {
            $events = DB::table('events')
            ->select('events.name AS event_name', 'events.description',
            'events.date_time', 'events.id AS event_id', 'users.id AS user_id')
            ->join('users', 'events.organiser_id', '=', 'users.id')
            ->where('events.organiser_id', '=', auth::id());
        }

        //filter
        if($request['category'] != "All")
        {
            $events = $this->listByCategory($events, $request['category']);
        }

        if($request['likeness_ranking'] != null)
        {
            $events = $this->listByLikenessRanking($events, $request['likeness_ranking']);
        }

        if($request['date_time_begin'] != null)
        {
            $events = $this->listByDate($events, $request['date_time_begin'], true);
        }

        if($request['date_time_end'] != null)
        {
            $events = $this->listByDate($events, $request['date_time_end'], false);
        }
        $events = $events->get();

        session()->reflash();
        $request->flash();
        return view('/list_events', array('events'=>$events))->withInput($request);
    }

    public function validateFilter(Request $request)
    {
        $rules = [
            'category' => 'in:All,Sport,Culture,Other',
            'likeness_ranking' => 'nullable|integer'
        ];

        $messages = [
            'category.in' => 'Please select one of the categories.',
            'likeness_ranking.integer' => 'The minimum likes must be an integer or empty.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect::back()->withErrors($validator)->withInput();
        }
        return $this->filterEvents($request);
    }

    //====================LIKE EVENT====================

    public function likeEvent(Request $request)
    {
        Event::find($request->event_id)->increment('likeness_ranking');
        return redirect()->back()->with('message', 'Event Liked');
    }

    //====================MODIFY EVENT====================

    //is show_event page but with buttons to modify and delete
    public function getModifyPage($id)
    {
        session()->flash('event_type', 'modify');
        $event = $this->getEventData($id);
        if(sizeOf($this->getEventData($id)) == 1) {
            abort(404);
        }
        $event['date_time'] = str_replace(' ', 'T', $event['date_time']);

        //Only allow user to modify their own event
        if(auth::id() == $event['user_id']) {
            return view('new_event')->with(array('event' => $event));
        }
        abort(404);
    }


    public function modify(Request $request)
    {
        $validator = $this->validateEvent($request);
        if($validator != 'fails') {
            session()->reflash();
            return redirect::back()->withErrors($validator)->withInput();
        }

        //====================IMAGE PROCESSING====================
        $image = $request->file('image');

        $event = Event::find($request->event_id);
        $event->name = $request->name;
        $event->category = $request->category;
        $event->date_time = $request->date_time;
        $event->description = $request->description;
        $event->place = $request->place;
        $event->image_file_path = $image->getClientOriginalName();

        $event->save();

        //remove old image(s) and store new image(s)]
        Storage::deleteDirectory('public/images/'.$request->event_id);
        Storage::makeDirectory('public/images/'.$event->id);
        $image->storeAs('images/'.$event->id, $image->getClientOriginalName(), 'public');

        return redirect('home')->with('message', 'Event Modified');
    }

    //====================DELETE EVENT====================
    public function delete($id)
    {
        $event = $this->getEventData($id);
        if(sizeOf($this->getEventData($id)) == 1) {
            abort(404);
        }
        if(auth::id() == $event['user_id']) {
            DB::table('events')->where('id', '=', $id)->delete();
            Storage::deleteDirectory('public/images/'.$id);

            return redirect('home')->with('message', 'Event Deleted');
        }
        abort(404);
    }

}
