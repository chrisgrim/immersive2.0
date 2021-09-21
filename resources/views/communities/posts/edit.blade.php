@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-create.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection
@section('content')
    <div id="bodyArea">

        @if ( session()->exists( 'submitted' ))
            <modal-wrapper :user= "{{auth()->user()}}" loadmessage="submitted"></modal-wrapper>
        @endif 
        <vue-post-edit
            :shelves="{{ $community->shelves }}"
            :community="{{ $community }}" 
            :owner="true"
            :value="{{ $post }}" 
            :user="{{ auth()->user() }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 