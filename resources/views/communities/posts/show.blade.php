@extends('layouts.master')

@section('meta')
    <title>{{$post->name}} {{ '- ' . \Illuminate\Support\Str::limit($post->blurb, 80) }} </title>
    <link rel="canonical" href="{{url()->current()}}">
    <meta name="description" content="{{$post->blurb}}"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="post" />
    <meta property="og:title" content="{{$post->name}}" />
    <meta property="og:description" content="{{$post->blurb}}" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="article:publisher" content="https://www.everythingimmersive.com" />
    <meta property="article:section" content="Posts" />
    <meta property="article:published_time" content="{{$post->created_at}}" />
    <meta property="article:modified_time" content="{{$post->updated_at}}" />
    <meta property="og:updated_time" content="{{$post->updated_at}}" />
    <meta property="og:image" content="{{ env('MIX_IMAGE_URL') }}{{$post->largeImagePath}}" />
    <meta property="og:image:secure_url" content="{{ env('MIX_IMAGE_URL') }}{{$post->largeImagePath}}" />
    <meta property="og:image:width" content="900" />
    <meta property="og:image:height" content="500" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{$post->blurb}}" />
    <meta name="twitter:title" content="{{$post->name}}" />
    <meta name="twitter:site" content="@everythingimmersive" />
    <meta name="twitter:image" content="{{ env('MIX_IMAGE_URL') }}{{$post->largeImagePath}}" />
    <meta name="twitter:creator" content="@everythingimmersive" />
    <script type="application/ld+json" class="yoast-schema-graph yoast-schema-graph--main">{"@context": "https://schema.org", "@graph": [{"@type": "Organization", "name": "{{config('app.name')}}", "url": "{{url('/')}}", "sameAs": [ "https://www.facebook.com/groups/{{config('app.name')}}/"], "logo":{"@type": "ImageObject", "@id": "{{url('/')}}/#logo", "url": "{{url('/')}}/storage/website-files/ei-logo.png", "width": 512, "height": 512, "caption": "{{config('app.name')}}"}},{"@type": "WebSite", "url": "{{url('/')}}", "name": "{{config('app.name')}}", "publisher":{"@id": "{{config('app.name')}}/#organization"}},{"@type": "WebPage", "@id": "{{url()->current()}}/#webpage", "url": "{{url()->current()}}", "name": "{{$post->name}}", "image":{"@type": "ImageObject", "@id": "{{url()->current()}}/#primaryimage", "url": "{{ env('MIX_IMAGE_URL') }}{{$post->largeImagePath}}", "width": 900, "height": 500, "caption": "{{$post->name}}"}, "primaryImageOfPage":{"@id": "{{url()->current()}}/#primaryimage"}, "datePublished": "{{$post->created_at}}", "dateModified": "{{$post->updated_at}}", "description": "{{$post->blurb}}"},{"@type": "Article", "@id": "{{url()->current()}}/#article", "author":{"@id": "{{url()->current()}}/#author"}, "headline": "{{$post->name}}", "datePublished": "{{$post->created_at}}", "dateModified": "{{$post->updated_at}}", "mainEntityOfPage": "{{url()->current()}}/#webpage", "publisher":{"@id": "{{url('/')}}/#organization"}, "image":{"@id": "{{url()->current()}}/#primaryimage"}},{"@type": ["Person"], "@id": "{{url()->current()}}/#author", "name": "{{$post->user->name}}", "image":{"@type": "ImageObject", "@id": "{{url('/')}}/#authorlogo", "url": "{{ env('MIX_IMAGE_URL') }}{{$post->user->largeImagePath}}", "caption": "{{$post->user->name}}"}, "sameAs": []}]}</script>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="post" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="post" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea">
        <vue-post-show
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :community="{{ $community }}" 
            :curator="{{ auth()->user() ? auth()->user()->can('update', $community) ? 'true' : 'false' : 'null' }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}"
            :value="{{ $post }}" />
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 