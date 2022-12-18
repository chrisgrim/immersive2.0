@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-create.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="postedit" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="postedit" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <modal-wrapper 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"
            loadmessage="{{ session('submitted') ? 'submitted' : '' }}"></modal-wrapper>
        <vue-post-edit
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :shelves="{{ $community->shelves()->where('status' , '!=' , 'a')->get() }}"
            :community="{{ $community }}" 
            :curator="{{ auth()->user() ? auth()->user()->can('update', $community) ? 'true' : 'false' : 'null' }}"
            :value="{{ $post }}" 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 