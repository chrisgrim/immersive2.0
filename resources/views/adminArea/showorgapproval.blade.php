@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <div class="org">
            <organizer-show :loadorganizer="{{ $organizer }}"></organizer-show> 
        </div>
        <div class="admin-approval__space">
            
        </div>
        <vue-org-approval-bar :loadorganizer="{{$organizer}}">
    </div>
@endsection