@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')
        <create-category :event="{{$event}}" :categories="{{$categories}}" />
    </div>
@endsection
