@extends('layouts.master')

@section('meta')
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet">
        @yield('location')

@endsection

@section('content')
    <div id="create">
        @yield('created')
    </div>  
@endsection