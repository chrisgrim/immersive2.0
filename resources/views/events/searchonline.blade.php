@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="searchonline" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav navtype="searchonline"></vue-nav>
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
        <vue-search-online 
            user="{{ auth()->id() }}" 
            :tags="{{ $tags }}" 
            :onlineevents="{{ $onlineevents }}" 
            :categories="{{ $categories }}">
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 