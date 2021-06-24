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

        @if ( session()->exists( 'submitted' ))
            <vue-alert 
            message="submitted">
            </vue-alert>
        @endif

        @guest
            <vue-community-show 
                :owner="false"
                :listings="{{ $listings }}" 
                :value="{{ $community }}"/>    
        @else
            @if ( $community->curators->contains('id', auth()->user()->id) )
                <vue-community-show
                    :owner="true"
                    :listings="{{ $listings }}" 
                    :value="{{ $community }}" 
                    :user="{{ auth()->user() }}"/>
            @else
                <vue-community-show
                    :owner="false"
                    :listings="{{ $listings }}" 
                    :value="{{ $community }}" 
                    :user="{{ auth()->user() }}"/>
            @endif
        @endauth
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 