@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')  
        <vue-create-review-event :tickets="{{$tickets}}" :loadevent="{{$event}}"></vue-create-review-event>
    </div>   
@endsection