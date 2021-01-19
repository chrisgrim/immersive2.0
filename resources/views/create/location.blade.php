@extends('create.index')

@section('location')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxpUKfSJMC4_3xwLU73AmH-jszjexoriw&libraries=places"></script>
@endsection

@section('created')
    <div class="create-body">
        @include('create.nav')
        <create-location-google :event="{{$event}}" :remote="{{$remote}}"/>
    </div>	
@endsection
