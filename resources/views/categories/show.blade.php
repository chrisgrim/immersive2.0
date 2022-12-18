@extends('layouts.master')

@section('meta')
    <title>{{$category->name}}</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @auth
        <vue-nav :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection

@section('content')
    <div id="bodyArea"> 
    </div>
@endsection