@extends('layouts.app')
<!-- S5. Click an event to view more details of the event including a picture,
person to contact venue etc. and showing your interest to the event by clicking
the Like button  -->
<!-- Styles -->
<link href="{{ asset('css/show_event.css') }}" rel="stylesheet">
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $event['event_name'] }}</div>
                @if(session()->has('message'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session()->get('message') }}
                </div>
                @endif
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="container col-6">

                                <div id="event_info" class="container">
                                    <div class="title text-md-center">
                                        <b>Event Information</b>
                                    </div>
                                    <br>

                                    <div class="row">
                                        <label class="col-md-3 text-md-right"><b>Category: </b></label>
                                        <div class="col-8">
                                            {{ $event['category'] }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-md-3 text-md-right"><b>Date: </b></label>
                                        <div class="col-8">
                                            {{ date_format(new DateTime($event['date_time']), 'd-M-Y') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-md-3 text-md-right"><b>Time: </b></label>
                                        <div class="col-8">
                                            {{ date_format(new DateTime($event['date_time']), 'H:i:s') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-md-3 text-md-right"><b>Place: </b></label>
                                        <div class="col-8">
                                            {{ $event['place'] }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-md-3 text-md-right"><b>Description: </b></label>
                                        <div class="col-8">
                                            {{ $event['description'] }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="container col-6">
                                <div id="contact_info" class="container">
                                    <div class="title text-md-center">
                                        <b>Contact Information</b>
                                    </div>
                                    <br>

                                    <div class="row">
                                        <label class="col-4 offset-3 text-md-right"><b>Event Organiser: </b></label>
                                        <div class="col-4">
                                            {{ $event['user_name'] }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-4 offset-3 text-md-right"><b>Contact Number: </b></label>
                                        <div class="col-4">
                                            {{ $event['user_phone_no'] }}
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div id="image" class="container">
                                    <div class="fill">
                                        <img src="{{asset('storage/images/'.$event['event_id'].'/'.$event['image_file_path'])}}"> </img>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-2 offset-1">
                                <form action="{{ route('like_event', ['id' => $event['event_id']]) }}" method="POST">
                                    @csrf
                                    <input name="event_id" type="hidden" value="{{$event['event_id']}}">

                                    @if(session()->has('message'))
                                    <button type="submit" class="btn btn-primary" disabled="true">
                                        {{ __('Liked') }}
                                    </button>
                                    @else
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Like') }}
                                    </button>
                                    @endif
                                    <label class="col-form-label">{{ $event['likeness_ranking'] }} Likes</label>
                                </form>
                            </div>



                            @if($event['user_id'] == auth::id())
                            <div class="col-md-1">
                                <a class="btn btn-primary" href="{{ route('event_modify_page', ['id' => $event['event_id']]) }}">{{ __('Edit') }}</a>
                            </div>

                            <div class="col-md-1">
                                <a class="btn btn-primary" href="{{ route('event_delete', ['id' => $event['event_id']]) }}">{{ __('Delete') }}</a>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
