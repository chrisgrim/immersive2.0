@extends('layouts.master')

@section('nav')
    @auth
        <vue-nav :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection

@section('content')
	<form method="POST" action="/categories" class="floating-form">
    @csrf
    	<input type="text" name="name" placeholder="name">
    	<input type="text" name="slug" placeholder="slug">
    	<input type="text" name="description" placeholder="description">
		<input type="text" name="imagePath" placeholder="imagePath">
        <button type="submit" class="create">
            Create
        </button>
	</form>
@endsection