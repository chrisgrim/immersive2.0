@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
        <vue-nav navtype="messageshow" :user= "{{auth()->user()}}"></vue-nav>
@endsection

@section('content')
    <div id="bodyArea">
        <message-show :loadmessages="{{ $conversation }}" :loaduser="{{ auth()->user() }}"></message-show> 
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 