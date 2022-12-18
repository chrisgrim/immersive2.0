@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <meta name="description" content="Your resource for immersive and interactive theatre, art, virtual reality, escape rooms, dance and more.">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{config('app.name')}}" />
    <meta property="og:description" content="Your resource for immersive and interactive theatre, art, virtual reality, escape rooms, dance and more." />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="article:publisher" content="https://www.facebook.com/webfxinc" />
    <meta property="article:section" content="Immersive" />
    <meta property="og:image" content="{{ url('/') }}/storage/website-files/ei-logo.png" />
    <meta property="og:image:secure_url" content="{{ url('/') }}/storage/website-files/ei-logo.png" />
    <meta property="og:image:width" content="1280" />
    <meta property="og:image:height" content="720" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="Your resource for immersive and interactive theatre, art, virtual reality, escape rooms, dance and more." />
    <meta name="twitter:title" content="{{config('app.name')}}" />
    <meta name="twitter:site" content="@everythingimmersive" />
    <meta name="twitter:image" content="{{ url('/') }}/storage/website-files/ei-logo.png" />
    <meta name="twitter:creator" content="@everythingimmersive" />
    <script type="application/ld+json">{"@context":"http://schema.org", "@type":"Organization", "address":{"@type":"PostalAddress", "addressLocality":"Petaluma", "addressRegion":"SF", "postalCode":"94952", "streetAddress":"600 East D St"}, "description": "Your resource for immersive and interactive theatre, art, virtual reality, escape rooms, dance and more.", "logo":"https://everythingimmersive.com/storage/website-files/ei-logo.png", "name":"Everything Immersive", "sameAs":[ "https://www.facebook.com/EverythingImmersive/", "https://www.linkedin.com/company/everythingimmersive", "https://www.instagram.com/everythingimmersive/", "https://twitter.com/everythingimmersive", "https://plus.google.com/+everythingimmersive", "https://en.wikipedia.org/wiki/everythingimmersive"], "url":"https://everythingimmersive.com"}</script>
    <link href="{{ mix('/css/app-lite.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
@endsection 

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="homepage" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="homepage" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div class="homepage" id="bodyArea">
        @if ( session()->exists( 'verifiy' )) 
            <vue-email-verify :user="{{auth()->user()}}" message="verify"></vue-email-verify>
        @endif
        @if($staffpicks)
            <index 
                :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
                :categories="{{$categories}}" 
                :docks="{{$docks}}"
                :tags="{{$tags}}" 
                :staffpicks="{{$staffpicks}}"/>
        @else
            <index 
                :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
                :docks="{{$docks}}"
                :tags="{{$tags}}" 
                :categories="{{$categories}}"/>
        @endif
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 