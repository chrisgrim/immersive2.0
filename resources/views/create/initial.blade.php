@extends('layouts.master')

@section('meta')
        <link href="{{ mix('/css/app-create.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @auth
        <vue-nav navtype="create" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
@endsection

@section('content')

<div id="bodyArea" class="show">
    <vue-create-first-event :user="{{auth()->user()}}"></vue-create-first-event> 
</div>

@endsection
