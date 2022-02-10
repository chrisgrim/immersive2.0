@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="org" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="org" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <organizer-create
            :loadorganizer="{{ !empty($organizer) ? $organizer : 'null' }}"
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"  />

    </div>
@endsection

