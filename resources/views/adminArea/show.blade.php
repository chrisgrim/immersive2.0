@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <div class="approve">
            <vue-admin-event-show :tickets="{{$tickets}}" :loadevent="{{$event}}">
        </div> 
    </div>
@endsection