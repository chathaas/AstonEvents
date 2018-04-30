@extends('layouts.app')
<!-- Styles -->
<link href="{{ asset('css/list_events.css') }}" rel="stylesheet">

<!-- Scripts -->
<script src="{{ asset('js/list_events.js') }}"></script>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ session()->get('filter_type') == 'all' ? __('All Events') : __('My Events') }}</div>
                <div class="card-body">
                    <!-- Filter form  -->
                    <form method="POST" action="{{ session()->get('filter_type') == 'all' ? route('filter_all_events') : route('filter_my_events') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="category" class="col-md-2 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-9">
                                <select name='category' class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}">
                                    <option value="All" {{ old('category') == 'All' ? 'selected' : '' }}>All</option>
                                    <option value="Sport" {{ old('category') == 'Sport' ? 'selected' : '' }}>Sport</option>
                                    <option value="Culture"{{ old('category') == 'Culture' ? 'selected' : '' }}>Culture</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>

                                @if ($errors->has('category'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('category') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="likeness_ranking" class="col-md-2 col-form-label text-md-right">{{ __('Minimum Likes') }}</label>
                            <div class="col-md-9">
                                <input id="likeness_ranking" type="text" class="form-control{{ $errors->has('likeness_ranking') ? ' is-invalid' : '' }}" value="{{ old('likeness_ranking') }}" name="likeness_ranking">
                                @if ($errors->has('likeness_ranking'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('likeness_ranking') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_time_begin" class="col-md-2 col-form-label text-md-right">{{ __('Date Start') }}</label>
                            <div class="col-md-4">
                                <input id="date_time_begin" type="datetime-local" class="form-control{{ $errors->has('date_time_begin') ? ' is-invalid' : '' }}" value="{{ old('date_time_begin') }}" name="date_time_begin">

                                @if ($errors->has('date_time_begin'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('date_time_begin') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-1">
                                    <label class="col-form-label text-md-right">{{ __('Date End') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="date_time_end" type="datetime-local" class="form-control{{ $errors->has('date_time_end') ? ' is-invalid' : '' }}" value="{{ old('date_time_end') }}" name="date_time_end">

                                @if ($errors->has('date_time_end'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('date_time_end') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Filter Events') }}
                                </button>
                            </div>

                        </form>
                    </div>

                    <!-- Space between filter form and results table -->
                    <br>
                    <br>

                    <!-- List of events -->
                    <div class="container">
                        <div class="row justify-content-center">
                            <table class="col-md-12" id="eventTable">
                                <thead>
                                    <tr class="row">
                                        <th class="col-2 offset-1" onclick="sortTable(0, 'string')"> Name </th>
                                        <th class="col-3" onclick="sortTable(1, 'string')"> Description </th>
                                        <th class="col-3" onclick="sortTable(2, 'date')"> Date & Time </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                    <tr class="row">
                                        <td class="col-2 offset-1 d-flex align-items-center"> {{$event->event_name}} </td>
                                        <td class="col-3 d-flex align-items-center"> {{$event->description}} </td>
                                        <td class="col-3 d-flex align-items-center"> {{date_format(new DateTime($event->date_time), 'd-M-Y H:i:s')}} </td>
                                        <td class="d-flex align-items-center">
                                            <a class="btn btn-primary" href="{{ route('show', ['id' => $event->event_id]) }}">{{ __('View') }}</a>
                                        </td>
                                        @if($event->user_id == auth::id())
                                        <td class="d-flex align-items-center">
                                            <a class="btn btn-primary" href="{{ route('event_modify_page', ['id' => $event->event_id]) }}">{{ __('Edit') }}</a>
                                        </td>
                                        <td class="d-flex align-items-center">
                                            <a class="btn btn-primary" href="{{ route('event_delete', ['id' => $event->event_id]) }}">{{ __('Delete') }}</a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
