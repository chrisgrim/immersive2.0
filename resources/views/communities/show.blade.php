@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
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
        @guest
            <vue-community-show 
                :owner="false"
                :shelves="{{ $shelves }}" 
                :value="{{ $community }}"/>
        @else
            @can('update', $community)
                <vue-community-show
                    :owner="true"
                    :shelves="{{ $shelves }}" 
                    :value="{{ $community }}" />
            @else
                <vue-community-show
                    :owner="false"
                    :shelves="{{ $shelves }}" 
                    :value="{{ $community }}" />
            @endcan
        @endauth
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 