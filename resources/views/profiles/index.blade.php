@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxpUKfSJMC4_3xwLU73AmH-jszjexoriw&libraries=places"></script>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection

@section('nav')
    @auth
        <vue-nav navtype="profile" :user="{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav navtype="profile"></vue-nav>
    @endguest
@endsection

@section('content')
	<div id="bodyArea">
		<user-profile :events="{{ $events }}" :loaduser="{{ $user }}" auth="{{auth()->id()}}" v-cloak></user-profile>	
	</div>
@endsection