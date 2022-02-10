@extends('layouts.master')

@section('meta')
    
    <title>{{$event->name}} {{$event->tag_line ? '- ' . \Illuminate\Support\Str::limit($event->tag_line, 80) : '- ' . \Illuminate\Support\Str::limit($event->description, 80)}} </title>
    <link rel="canonical" href="{{url()->current()}}">
    <meta name="description" content="{{$event->tag_line ? $event->tag_line : $event->description}}"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="event" />
    <meta property="og:title" content="{{$event->name}}" />
    <meta property="og:description" content="{{$event->tag_line ? $event->tag_line : $event->description}}" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="article:publisher" content="https://www.everythingimmersive.com" />
    <meta property="article:section" content="Events" />
    <meta property="article:published_time" content="{{$event->created_at}}" />
    <meta property="article:modified_time" content="{{$event->updated_at}}" />
    <meta property="og:updated_time" content="{{$event->updated_at}}" />
    <meta property="og:image" content="{{ url('/') }}/storage/{{$event->largeImagePath}}" />
    <meta property="og:image:secure_url" content="{{ url('/') }}/storage/{{$event->largeImagePath}}" />
    <meta property="og:image:width" content="1280" />
    <meta property="og:image:height" content="720" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{$event->tag_line ? $event->tag_line : $event->description}}" />
    <meta name="twitter:title" content="{{$event->name}}" />
    <meta name="twitter:site" content="@everythingimmersive" />
    <meta name="twitter:image" content="{{ url('/') }}/storage/{{$event->largeImagePath}}" />
    <meta name="twitter:creator" content="@everythingimmersive" />
    @if($event->hasLocation)
        <script type="application/ld+json">{"@context": "https://schema.org", "@type": "Event", "name": "{{$event->name}}{{$event->tag_line ? '- ' . \Illuminate\Support\Str::limit($event->tag_line, 80) : '- ' . \Illuminate\Support\Str::limit($event->description, 80)}}", @if($event->shows->isEmpty()) "startDate":{{\Carbon\Carbon::parse($event->created_at)->toIso8601String()}}, @else "startDate":"{{\Carbon\Carbon::parse($event->shows[0]->date)->toIso8601String()}}", @endif "endDate": "{{\Carbon\Carbon::parse($event->closingDate)->toIso8601String()}}", "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode", "eventStatus": "https://schema.org/EventScheduled", "location":{"@type": "Place", "name": "{{$event->location->venue ? $event->location->venue : $event->name}}", "address":{"@type": "PostalAddress", "streetAddress": "{{$event->location->home . ' ' . $event->location->street}}", "addressLocality": "{{$event->location->city}}", "postalCode": "{{$event->location->postal_code}}", "addressRegion": "{{$event->location->region}}", "addressCountry": "{{$event->location->country}}"}}, "image":"/storage/{{$event->largeImagePath}}", "description": "{{$event->tag_line ? $event->tag_line : $event->description}}", "offers":{"@type": "Offer", "url": "{{$event->ticketUrl}}", "price": "{{$event->priceranges[0]->price}}", "priceCurrency": "USD", "availability": "https://schema.org/InStock", "validFrom": "{{$event->priceranges[0]->created_at}}"}, "organizer":{"@type": "Organization", "name": "{{$event->organizer->name}}", "url": "{{$event->organizer->website ? $event->organizer->website : Request::root() .'/organizer/' . $event->organizer->slug}}"}}</script>
    @else
        <script type="application/ld+json">{"@context": "https://schema.org", "@type": "Event", "name": "{{$event->name}}{{$event->tag_line ? '- ' . \Illuminate\Support\Str::limit($event->tag_line, 80) : '- ' . \Illuminate\Support\Str::limit($event->description, 80)}}", @if($event->shows->isEmpty()) "startDate":{{\Carbon\Carbon::parse($event->created_at)->toIso8601String()}}, @else "startDate":"{{\Carbon\Carbon::parse($event->shows[0]->date)->toIso8601String()}}", @endif "endDate": "{{\Carbon\Carbon::parse($event->closingDate)->toIso8601String()}}", "eventStatus": "https://schema.org/EventScheduled", "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode", "location":{"@type": "VirtualLocation", "url": "{{$event->websiteUrl ? $event->websiteUrl : ($event->ticketUrl ? $event->ticketUrl : Request::url())}}"}, "image":"/storage/{{$event->largeImagePath}}", "description": "{{$event->tag_line ? $event->tag_line : $event->description}}", "offers":{"@type": "Offer", "url": "{{$event->ticketUrl ? $event->ticketUrl : ($event->websiteUrl ? $event->websiteUrl : Request::url())}}", "price": "{{$event->priceranges[0]->price}}", "priceCurrency": "USD", "availability": "https://schema.org/InStock", "validFrom": "{{$event->priceranges[0]->created_at}}"}, "organizer":{"@type": "Organization", "name": "{{$event->organizer->name}}", "url": "{{$event->organizer->website ? $event->organizer->website : Request::root() .'/organizer/' . $event->organizer->slug}}"}}</script>
    @endif
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet" media="print" onload="this.media='all'; this.onload=null;">
@endsection

@section('nav')
    @if (Browser::isMobile())
        <vue-nav-mobile navtype="show" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @else
        <vue-nav navtype="show" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" />
    @endif
@endsection

@section('content')
    <div id="bodyArea" class="show">
        <modal-wrapper :user= "{{ auth()->user() ? auth()->user() : 'null' }}"></modal-wrapper>
        <event-show 
            :loadevent="{{$event}}" 
            :tickets="{{$tickets}}" 
            :mobile="{{ Browser::isMobile() ? Browser::isMobile() : 'null' }}"
            :user="{{ auth()->user() ? auth()->user() : 'null' }}" />  
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 