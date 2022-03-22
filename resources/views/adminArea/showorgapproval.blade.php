@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <organizer-show 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :organizer="{{ $organizer }}"></organizer-show>
        <div class="h-80" />
        <vue-org-approval-bar :loadorganizer="{{$organizer}}">
    </div>
@endsection