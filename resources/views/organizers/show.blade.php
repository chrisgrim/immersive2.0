@extends('layouts.master')

@section('meta')
    <script type="application/ld+json">
        {
        "@context":"http://schema.org",
        "@type":"Organization",
        "description": "{{$organizer->description}}",
        "name": "{{$organizer->name}} {{ '- ' . \Illuminate\Support\Str::limit($organizer->description, 80)}}",
        "sameAs": @json($organizer->getHandles()),
        @if ($organizer->website) 
            "url":"{{$organizer->website}}",
        @else 
            "url":"{{ url('/') }}/organizer/{{$organizer->slug}}",
        @endif
        @if ($organizer->largeImagePath) 
            "logo":"{{ url('/') }}/storage/{{$organizer->largeImagePath}}"}
        @else
            "logo":"{{ url('/') }}/storage/website-files/schema-organizer.png"}
        @endif
    </script>

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{$organizer->description}}" />
    <meta name="twitter:title" content="{{$organizer->name}}" />
    @if ($organizer->twitterHandle) 
        <meta name="twitter:site" content="@{{$organizer->twitterHandle}}" />
        <meta name="twitter:creator" content="@{{$organizer->twitterHandle}}" />
    @endif
    @if ($organizer->largeImagePath) 
        <meta name="twitter:image" content="{{ url('/') }}/storage/{{$organizer->largeImagePath}}" />
    @endif

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{$organizer->name}}" />
    <meta property="og:description" content="{{$organizer->description}}" />
    <meta property="og:url" content="{{ url('/') }}/organizer/{{$organizer->slug}}" />
    <meta property="og:site_name" content="{{$organizer->name}}" />
    @if ($organizer->largeImagePath) 
        <meta property="og:image" content="{{ url('/') }}/storage/{{$organizer->largeImagePath}}" />
        <meta property="og:image:secure_url" content="{{ url('/') }}/storage/{{$organizer->largeImagePath}}" />
        <meta name="twitter:image" content="{{ url('/') }}/storage/{{$organizer->largeImagePath}}" />
    @endif

    <title>{{$organizer->name}}</title>
    <script> window.myToken =  <?php echo json_encode(['csrfToken' => csrf_token()]); ?></script>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
@endsection

@section('nav')
    @auth
        <vue-nav navtype="org" :user="{{auth()->user()}}" />
    @endauth
    @guest
        <vue-nav navtype="org" />
    @endguest
@endsection

@section('content')
    <div id="bodyArea" class="org">
        @auth
            <organizer-show :user="{{auth()->user()}}" :loadorganizer="{{ $organizer }}"></organizer-show> 
        @endauth
        @guest
            <organizer-show :loadorganizer="{{ $organizer }}"></organizer-show> 
        @endguest
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 
