@extends('layouts.master')

@section('meta')

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{$organizer->description}}" />
    <meta name="twitter:title" content="{{$organizer->name}}" />
    @if ($organizer->twitterHandle) 
        <meta name="twitter:site" content="@{{$organizer->twitterHandle}}" />
        <meta name="twitter:creator" content="@{{$organizer->twitterHandle}}" />
    @endif
    @if ($organizer->largeImagePath) 
        <meta name="twitter:image" content="{{ env('MIX_IMAGE_URL') }}{{$organizer->largeImagePath}}" />
    @endif

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{$organizer->name}}" />
    <meta property="og:description" content="{{$organizer->description}}" />
    <meta property="og:url" content="{{ url('/') }}/organizer/{{$organizer->slug}}" />
    <meta property="og:site_name" content="{{$organizer->name}}" />
    @if ($organizer->largeImagePath) 
        <meta property="og:image" content="{{ env('MIX_IMAGE_URL') }}{{$organizer->largeImagePath}}" />
        <meta property="og:image:secure_url" content="{{ env('MIX_IMAGE_URL') }}{{$organizer->largeImagePath}}" />
        <meta name="twitter:image" content="{{ env('MIX_IMAGE_URL') }}{{$organizer->largeImagePath}}" />
    @endif
    <title>{{$organizer->name}}</title>
    <script type="application/ld+json">{"@context":"http://schema.org", "@type":"Organization", "description": "{{$organizer->description}}", "name": "{{$organizer->name}}{{'- ' . \Illuminate\Support\Str::limit($organizer->description, 80)}}", "sameAs": @json($organizer->getHandles()), @if ($organizer->website) "url":"{{$organizer->website}}", @else "url":"{{url('/')}}/organizer/{{$organizer->slug}}", @endif @if ($organizer->largeImagePath) "logo":"{{ env('MIX_IMAGE_URL') }}{{$organizer->largeImagePath}}"}@else "logo":"{{url('/')}}/storage/website-files/schema-organizer.png"}@endif </script>
    <script> window.myToken =  <?php echo json_encode(['csrfToken' => csrf_token()]); ?></script>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet" onload="this.media='all'; this.onload=null;">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="org" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="org" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea" class="org">
        <modal-wrapper :user= "{{ auth()->user() ? auth()->user() : 'null' }}"></modal-wrapper>
        <organizer-show 
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :organizer="{{ $organizer }}">
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 
