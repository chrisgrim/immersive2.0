@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="searchlocation" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="searchlocation" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper :user= "{{ auth()->user() ? auth()->user() : 'null' }}"></modal-wrapper>
        <vue-search-location 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :tags="{{ $tags }}" 
            :searchedevents="{{ $searchedevents }}" 
            :docks="{{ $docks }}" 
            :categories="{{ $categories }}">
    </div>
@endsection

@section('footer')
    <vue-footer :visible="false"></vue-footer>
@endsection 