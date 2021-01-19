@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')	
        <create-description :event="{{$event}}" :loadtags="{{$tags}}"></create-description>
    </div>
@endsection
