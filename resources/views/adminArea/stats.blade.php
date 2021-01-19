@extends('adminArea.index')

@section('adminarea')
    <vue-admin-stats :usercount="{{$usercount}}" :eventcount="{{$eventcount}}"></vue-admin-stats>
@endsection
