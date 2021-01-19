@extends('create.index')

@section('created')	
    <div class="create-body">
        @include('create.nav')
        <create-shows :event="{{$event}}" :timezones="{{$timezones}}"></create-shows>
    </div>
@endsection