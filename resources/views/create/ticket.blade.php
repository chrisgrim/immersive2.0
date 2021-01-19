@extends('create.index')

@section('created') 
    <div class="create-body">
        @include('create.nav')
        <vue-create-tickets :event="{{$event}}"></vue-create-tickets>
    </div>
@endsection