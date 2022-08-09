@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-create.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="comedit" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="comedit" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"
            loadmessage="{{ session('submitted') ? session('submitted') : '' }}"></modal-wrapper>
        <vue-community-edit
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :loadowner="{{$community->owner()->first()}}"
            :loadshelves="{{ json_encode($shelves) }}" 
            :loadcommunity="{{ $community }}" 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 