@extends('adminArea.index')

@section('adminarea')
    <vue-community-admin-approval :value="{{ json_encode($communities) }}" />
@endsection