@extends('creation.main')

@section('created')
    <vue-creation-idea 
        :tags="{{$tags}}" 
        :type="{{true}}"
        :event="{{$event}}" 
        :remote="{{$remote}}"
        :categories="{{$categories}}" />
@endsection