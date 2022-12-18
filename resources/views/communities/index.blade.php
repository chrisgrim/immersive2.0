@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="comindex" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="comindex" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"
            loadmessage="{{ session('submitted') ? session('submitted') : '' }}"></modal-wrapper>   
        <vue-community-index 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :value="{{$communities}}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 