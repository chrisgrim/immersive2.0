@extends('layouts.master')

@section('meta')
    <title>Search Events</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="searchpage" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="searchpage" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper :user= "{{ auth()->user() ? auth()->user() : 'null' }}"></modal-wrapper>
        @if (Browser::isMobile())
            <vue-search-all-mobile 
                :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
                :tags="{{ $tags }}" 
                :searched-events="{{ json_encode($searchedEvents) }}" 
                :searched-Categories="{{ json_encode($searchedCategories) }}"
                :searched-Tags="{{ json_encode($searchedTags) }}"
                :categories="{{ $categories }}">
        @else
            <vue-search-all 
                :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
                :tags="{{ $tags }}" 
                :searched-events="{{ json_encode($searchedEvents) }}" 
                :searched-Categories="{{ json_encode($searchedCategories) }}"
                :searched-Tags="{{ json_encode($searchedTags) }}"
                :categories="{{ $categories }}">
        @endif
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 