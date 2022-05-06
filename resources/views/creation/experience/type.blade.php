@extends('creation.main')

@section('created')
    <vue-creation-index 
        :tags="{{$tags}}" 
        type="I-T"
        :event="{{$event}}" 
        :remote="{{$remote}}"
        :categories="{{$categories}}" />
@endsection