@extends('adminArea.index')

@section('adminarea')
    <dock-index 
            :loaddocks="{{ $docks }}"
            :user="{{ auth()->user() }}"/>    
@endsection
