@extends('create.index')

@section('created')
    <div class="create-body">
        <vue-create-organizer-nav :user="{{auth()->user()}}" :organizer="{{$organizer}}"></vue-create-organizer-nav>
        <vue-create-nav-bottom-bar ></vue-create-nav-bottom-bar> 
        <create-organizer :loadorganizer="{{$organizer}}" :user="{{auth()->user()}}" />
    </div>
@endsection
