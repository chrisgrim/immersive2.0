@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="favorited" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
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
        <vue-user-favorited :events="{{ $events }}" :loaduser="{{ auth()->user() }}" v-cloak></vue-user-favorited> 
    </div>
@endsection