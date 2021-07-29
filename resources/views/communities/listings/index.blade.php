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
            <vue-alert 
            message="submitted">
            </vue-alert>
        @endif
        <vue-listing-index
            :loadowner="{{$community->owner()->first()}}"
            :loadshelves="{{ $shelves }}" 
            :loadcommunity="{{ $community }}" 
            :user="{{ auth()->user() }}"/>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 