@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="searchpage" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav navtype="searchpage"></vue-nav>
    @endguest
@endsection

@section('content')
    <div id="bodyArea">
        <vue-search-all user="{{ auth()->id() }}" :tags="{{ $tags }}" :allevents="{{ $allevents }}" :categories="{{ $categories }}">
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 