@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="profile" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection

@section('content')
    <div class="account-index">
        <div class="account-notifications_header">
            <h2>Disclaimer</h2>
        </div>
        <section class="account-index-content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum voluptatibus illum voluptatem voluptas itaque, sequi necessitatibus illo pariatur delectus vero ipsam ad, eaque, blanditiis adipisci doloremque reprehenderit ipsa eius ab?
        </section>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 