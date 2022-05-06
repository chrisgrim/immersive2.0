@extends('adminArea.index')

@section('adminarea')
    <vue-admin-events :user="{{auth()->user()}}" />
@endsection