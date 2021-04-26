@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')
        <create-category 
            :tags="{{$tags}}" 
            :event="{{$event}}" 
            :categories="{{$categories}}" />
    </div>
@endsection
