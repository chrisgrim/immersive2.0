@extends('adminArea.index')

@section('adminarea')
    <approval-list user="{{auth()->id()}}"></approval-list>
@endsection