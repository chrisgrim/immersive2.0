@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxpUKfSJMC4_3xwLU73AmH-jszjexoriw&libraries=places"></script>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="profile" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="profile" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <user-profile 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :owner="{{ auth()->user() ? auth()->user()->can('update', $user) ? 'true' : 'false' : 'null' }}"
            :loaduser="{{ $user }}" 
            v-cloak>   
    </div>
@endsection