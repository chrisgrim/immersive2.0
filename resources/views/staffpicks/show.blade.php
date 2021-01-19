@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="staffpicks" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection

@section('content')
    <div id="bodyArea">
        <vue-staffpicks-show week="{{ $week }}" :staffpicks="{{ $staffpicks }}"></vue-staffpicks-show> 
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 