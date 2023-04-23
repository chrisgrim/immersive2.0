<section>
    <div class="border-b py-8 px-8 md:py-8 md:px-0 md:py-12">
        <h1 class="mb-4 leading-[3rem]">{{ $event->name }}</h1>
        <p class="mb-4 italic">{{ $event->tag_line }}</p>
        @if($event->hasLocation)
            <a class="flex underline text-xl font-semibold" href="#location">
                @if($event->location->city)
                    {{ $event->location->city }}
                @endif
                @if($event->location->city && $event->location->region)
                    , &nbsp;
                @endif
                @if($event->location->region)
                    {{ $event->location->region }}
                @endif
            </a>
        @else
            <p>Available anywhere</p>
        @endif
    </div>
    <div class="py-12 px-8 md:py-16 md:px-0 border-b">
        <div class="flex gap-4 justify-between items-center mb-4">
            <h2 class="inline-block text-3xl md:text-4xl">Experience hosted by
                <a href="{{ route('organizer.show', $event->organizer->slug) }}">
                    {{ $event->organizer->name }}
                </a>
            </h2>
            @if($event->organizer->thumbImagePath)
                <a class="min-w-[5rem]" href="{{ route('organizer.show', $event->organizer->slug) }}">
                    <picture>
                        <source type="image/webp" srcset="{{ config('app.image_url') }}{{ $event->organizer->thumbImagePath }}">
                        <img class="w-20 h-20 rounded-full float-right" src="{{ config('app.image_url') }}{{ substr($event->organizer->thumbImagePath, 0, -4) }}jpg" alt="Logo of {{ $event->organizer->name }}">
                    </picture>
                </a>
            @endif
        </div>
        <p class="relative overflow-auto" style="white-space: pre-wrap;">{{ implode(' ', array_slice(explode(' ', $event['description']), 0, 100)) }} <show-more text="{{ $event['description']}}" :limit="100" />
        </p>
        
    </div>
    @if($event->video)
        <div class="w-full p-8 md:py-16 md:px-0">
            <video-player
                alt="{{$event->name . ' Immersive Event'}}"
                src="{{$event->video}}"/>
        </div>
    @endif
</section>