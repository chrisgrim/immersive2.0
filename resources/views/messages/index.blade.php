@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="message" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="message" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection


@section('content')
    <div id="bodyArea">
        <message-index 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :events="{{ $events }}" >
    </div>
@endsection