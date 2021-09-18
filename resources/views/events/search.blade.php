@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="searchlocation" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav navtype="searchlocation"></vue-nav>
    @endguest
@endsection

@section('content')
    <div id="bodyArea">
        @auth
            <modal-wrapper :user= "{{auth()->user()}}"></modal-wrapper>
        @endauth
        @guest
            <modal-wrapper></modal-wrapper>
        @endguest
        <vue-search-location user="{{ auth()->id() }}" :tags="{{ $tags }}" :searchedevents="{{ $searchedevents }}" :onlineevents="{{ $onlineevents }}" :categories="{{ $categories }}">
    </div>
@endsection

@section('footer')
    <vue-footer :visible="false"></vue-footer>
@endsection 