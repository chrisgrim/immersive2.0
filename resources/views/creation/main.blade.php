@extends('layouts.master')

@section('meta')
        <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
        @yield('location')
@endsection

@section('content')
    <div id="create">
        @yield('created')
    </div>  
@endsection