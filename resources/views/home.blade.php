@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                @if(session()->has('message'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session()->get('message') }}
                </div>
                @endif
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    You are logged in!

                    <p>
                        <br>
                        As an event organiser you can create new events! <br>
                        You can also edit and delete any of your existing events! <br>
                        Finally you can view all events as well as your own events! <br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
