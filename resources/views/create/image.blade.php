@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')	
        <create-image :event="{{$event}}" />
    </div>
@endsection