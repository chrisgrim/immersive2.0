@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app-create.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="comcreate" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="comcreate" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">   
        <vue-community-create 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 