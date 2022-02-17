@extends('adminArea.index')

@section('adminarea')
    <vue-admin-communities-index :value="{{ json_encode($communities) }}" />
@endsection