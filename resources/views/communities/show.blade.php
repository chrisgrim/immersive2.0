@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="comshow" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="comshow" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <vue-community-show
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :curator="{{ auth()->user() ? auth()->user()->can('update', $community) ? 'true' : 'false' : 'null' }}"
            :loadshelves="{{ json_encode($shelves) }}" 
            :value="{{ $community }}" />
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 