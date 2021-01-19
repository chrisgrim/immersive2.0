@extends('adminArea.index')

@section('adminarea')
    <vue-org-approval-list user="{{auth()->id()}}" :organizers="{{$organizers}}"></vue-org-approval-list>
@endsection