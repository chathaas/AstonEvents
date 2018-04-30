@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ session()->get('event_type') == 'new' ? __('Create New Event') : __('Modify Event') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ session()->get('event_type') == 'new' ? route('store') : route('event_modify', ['id' => $event['event_id']]) }}"  enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Event Name') }}</label>

                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ session()->get('event_type') == 'new' ? old('name') : $event['event_name'] }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category" class="col-md-2 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-9">
                                <select name='category' class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" required>
                                    <option value="selected" hidden>Select a category</option>
                                    @if (session()->get('event_type') == 'new')
                                        <option value="Sport" {{ old('category') == 'Sport' ? 'selected' : '' }}>Sport</option>
                                        <option value="Culture" {{ old('category') == 'Culture' ? 'selected' : '' }}>Culture</option>
                                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                    @else
                                        <option value="Sport" {{ $event['category'] == 'Sport' ? 'selected' : '' }}>Sport</option>
                                        <option value="Culture" {{ $event['category'] == 'Culture' ? 'selected' : '' }}>Culture</option>
                                        <option value="Other" {{ $event['category'] == 'Other' ? 'selected' : '' }}>Other</option>
                                    @endif
                                </select>
                                @if ($errors->has('category'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time" class="col-md-2 col-form-label text-md-right">{{ __('Date & Time') }}</label>

                            <div class="col-md-9">
                                <input id="date_time" type="datetime-local" class="form-control{{ $errors->has('date_time') ? ' is-invalid' : '' }}" name="date_time" value="{{ session()->get('event_type') == 'new' ? old('date_time') : $event['date_time'] }}" required>
                                @if ($errors->has('date_time'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_time') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-2 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-9">
                                <textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" required>{{ session()->get('event_type') == 'new' ? old('description') : $event['description'] }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="place" class="col-md-2 col-form-label text-md-right">{{__('Place')}}</label>

                            <div class="col-md-9">
                                <input id="place" type="text" class="form-control{{ $errors->has('place') ? ' is-invalid' : '' }}" name='place' value="{{ session()->get('event_type') == 'new' ? old('place') : $event['place'] }}" required>

                                @if ($errors->has('place'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('place') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="image" class="col-md-2 col-form-label text-md-right">{{__('Image')}}</label>

                            <div class="col-md-9">
                                <input id="image" type="file" accept="image/*" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name='image' value="{{ session()->get('event_type') == 'new' ? old('image') : $event['image_file_path'] }}" required>
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if (session()->get('event_type') == 'modify')
                            <input name="event_id" type="hidden" value="{{$event['event_id']}}">
                        @endif


                        <div class="form-group row mb-0">
                            <div class="col-md-9 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ session()->get('event_type') == 'new' ? __('Create') : __('Modify') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
