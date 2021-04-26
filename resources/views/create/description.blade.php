@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')	
        <create-description :loadevent="{{$event}}"></create-description>
    </div>
@endsection
