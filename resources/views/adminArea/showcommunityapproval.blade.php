@extends('layouts.master')

@section('meta')
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="bodyArea">
        <div class="com">
            <vue-community-show 
                :owner="false"
                :sections="{{ $sections }}" 
                :value="{{ $community }}"/>    
        </div>
        <div class="admin-approval__space">
            
        </div>
        <vue-community-approval-bar :value="{{ $community }}">
    </div>
@endsection