@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
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
        <vue-user-account 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :user= "{{ auth()->user() ? auth()->user() : 'null' }}"
            v-cloak>
    </div>
@endsection