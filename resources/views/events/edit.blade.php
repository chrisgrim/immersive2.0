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
		<vue-event-edit 
            :allevents="{{auth()->user()->events()->whereIn('status', ['p','e'])->get()}}"
            :unpublished="{{auth()->user()->events()->whereNotIn('status', ['p','e'])->count()}}"
            :published="{{auth()->user()->events()->whereIn('status', ['p','e'])->count()}}"
            :user="{{auth()->user()}}"/>	
	</div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 