@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <event-show 
            :loadevent="{{$event}}" 
            :tickets="{{$tickets}}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"></event-show> 
        <organizer-show 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :organizer="{{ $event->organizer }}"></organizer-show>
        

        <div class="h-80" />
        @if($eventExists)
            <approval-bar :loadevent="{{$event}}" :exists="{{$eventExists}}">
        @else 
            <approval-bar :loadevent="{{$event}}" exists="{{$eventExists}}">
        @endif
    </div>
@endsection