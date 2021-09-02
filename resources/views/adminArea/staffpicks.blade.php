@extends('adminArea.index')

@section('adminarea')
    <admin-staff-picks 
        :loaduser= "{{auth()->user()}}"
        :loadstaff="{{$staff}}" />
@endsection