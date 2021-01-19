@isset($event)
    <vue-create-nav :user="{{auth()->user()}}" :event="{{$event}}"></vue-create-nav>
    <vue-create-nav-bottom-bar :event="{{$event}}"></vue-create-nav-bottom-bar> 
@else
    <vue-create-nav :user="{{auth()->user()}}"  event="null"></vue-create-nav>
    <vue-create-nav-bottom-bar ></vue-create-nav-bottom-bar> 
@endisset