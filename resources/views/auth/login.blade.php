@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}} - Login</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    <vue-nav navtype="entry"></vue-nav>
@endsection 

@section('content')
    <div id="bodyArea">
        <vue-register></vue-register>
    </div>
@endsection