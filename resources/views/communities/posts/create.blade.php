@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app-create.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="postcreate" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="postcreate" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">

        <modal-wrapper 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"
            message="{{ session('submitted') ? 'submitted' : '' }}"></modal-wrapper>
        <vue-post-create 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :shelves="{{ $community->shelves()->where('status', '!=', 'a')->get() }}"
            :community="{{ $community }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"/>    
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 