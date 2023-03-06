@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="searchonline" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="searchonline" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper :user= "{{ auth()->user() ? auth()->user() : 'null' }}"></modal-wrapper>
        @if (Browser::isMobile())
            <vue-search-online-mobile 
                :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
                :tags="{{ $tags }}" 
                :searched-events="{{ $searchedevents }}" 
                :at-home-categories="{{ $atHomeCategories }}"
                :searched-Categories="{{ json_encode($searchedCategories) }}"
                :searched-Tags="{{ json_encode($searchedTags) }}"
                :categories="{{ $categories }}">
        @else
            <vue-search-online 
                :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
                :tags="{{ $tags }}" 
                :searched-events="{{ $searchedevents }}" 
                :at-home-categories="{{ $atHomeCategories }}"
                :searched-Categories="{{ json_encode($searchedCategories) }}"
                :searched-Tags="{{ json_encode($searchedTags) }}"
                :categories="{{ $categories }}">
        @endif
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 