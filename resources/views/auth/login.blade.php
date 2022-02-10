@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}} - Login</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="entry" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="entry" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <vue-login :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}">
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 