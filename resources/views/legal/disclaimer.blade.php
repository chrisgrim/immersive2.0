@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
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
    <div class="disclaimer section-a">
        <div class="section-a__wrapper">
            <div class="name">
                <h2>Disclaimer</h2>
            </div>
            <section>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum voluptatibus illum voluptatem voluptas itaque, sequi necessitatibus illo pariatur delectus vero ipsam ad, eaque, blanditiis adipisci doloremque reprehenderit ipsa eius ab?
            </section>
        </div>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 