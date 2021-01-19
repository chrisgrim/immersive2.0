@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <div class="approve">
            <event-show :tickets="{{$tickets}}" :loadevent="{{$event}}">
        </div> 
        <div class="org">
            <organizer-show :loadorganizer="{{ $event->organizer }}"></organizer-show> 
        </div>
        <div class="admin-approval__space">
            
        </div>
        @if($eventExists)
            <approval-bar :loadevent="{{$event}}" :exists="{{$eventExists}}">
        @else 
            <approval-bar :loadevent="{{$event}}" exists="{{$eventExists}}">
        @endif
    </div>
@endsection