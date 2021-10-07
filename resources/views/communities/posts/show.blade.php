@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="post" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav navtype="post"></vue-nav>
    @endguest
@endsection
@section('content')
    <div id="bodyArea">

        @guest
            <vue-post-show
                :community="{{ $community }}" 
                :owner="false"
                :value="{{ $post }}" />
        @endguest

        @auth
            <vue-post-show
                :community="{{ $community }}" 
                :owner="{{ $community->curators->contains('id', auth()->user()->id) ? 'true' : 'false' }}"
                :value="{{ $post }}" />
        @endauth

    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 