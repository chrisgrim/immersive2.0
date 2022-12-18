@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div id="admin" class="w-full grid grid-cols-5 h-screen">
        <admin-nav navtype="a" :user="{{auth()->user()}}"></admin-nav>
        <div class="flex flex-col col-span-4 relative h-full p-8">
            @yield('adminarea')
        </div>
    </div>  
@endsection

