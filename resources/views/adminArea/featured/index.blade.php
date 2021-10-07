@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-admin.css') }}" rel="stylesheet">
@endsection

@section('nav')
    <admin-nav navtype="a" :loaduser="{{auth()->user()}}"></admin-nav>
@endsection

@section('content')
    <div id="admin">
        <dock-index 
            :loaddocks="{{ $docks }}"
            :user="{{ auth()->user() }}"/>    
    </div>  
@endsection
