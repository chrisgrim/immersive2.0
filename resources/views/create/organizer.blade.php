@extends('create.index')

@section('created')
    <div class="create-body">
        <vue-create-organizer-nav :user="{{auth()->user()}}"></vue-create-organizer-nav>
        <vue-create-nav-bottom-bar ></vue-create-nav-bottom-bar> 
        <create-organizer :user="{{auth()->user()}}" />
    </div>
@endsection
