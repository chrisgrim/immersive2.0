@extends('layouts.master')

@section('meta')
        <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxpUKfSJMC4_3xwLU73AmH-jszjexoriw&libraries=places"></script>
        @yield('location')

@endsection

@section('content')
    <div id="create">
        @yield('created')
    </div>  
@endsection