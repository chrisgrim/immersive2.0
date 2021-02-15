@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <div class="approve">
            <vue-admin-event-show :tickets="{{$tickets}}" :loadevent="{{$event}}">
        </div> 
    </div>
@endsection