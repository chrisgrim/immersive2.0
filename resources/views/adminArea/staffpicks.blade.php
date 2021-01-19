@extends('adminArea.index')

@section('adminarea')
    <admin-staff-picks :loadstaff="{{$staff}}" />
@endsection