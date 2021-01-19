@extends('create.index')

@section('created')
    <div class="create-body">
        @include('create.nav')
        <create-advisories :event="{{$event}}" :agelimit="{{$agelimit}}" :loadcontact="{{$contactAdvisories}}" :loadcontent="{{$contentAdvisories}}" :loadmobility="{{$mobilityAdvisories}}" :loadinteractive="{{$interactiveLevels}}"></create-advisories>
    </div>
@endsection

