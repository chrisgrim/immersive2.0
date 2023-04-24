<header class="min-h-[200px] relative w-full m-auto p-0 md:px-12 lg:px-32 lg:max-w-screen-xl">
        <nav>
            @if (Browser::isMobile())
                <a href="javascript:history.back()">
                    <button 
                        aria-label="back button"
                        type="button"
                        class="absolute flex top-8 left-8 z-10 p-1 rounded-full shadow-lg bg-white">
                        <svg class="h-12 w-12">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                </a>
                <event-show-favorite :mobile="true" :event="{{ $event }}" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" /></event-show-favorite>
            @else
                <div class="h-32 py-6 flex items-center justify-between">
                    <p class="text-xl">
                        <a href="/" class="underline">Everything Immersive</a> > <a href="/index/search?category={{ $event['category']['id'] }}&searchType=allEvents" class="underline">{{ $event['category']['name'] }}</a> > {{ $event['name'] }}
                    </p>
                    <event-show-favorite :mobile="false" :event="{{ $event }}" :user= "{{ auth()->user() ? auth()->user() : 'null' }}" /></event-show-favorite>
                </div> 
            @endif
        </nav>
    <picture>
        <source type="image/webp" srcset="{{ config('app.image_url') }}{{ $event['largeImagePath'] }}">
        <img class="min-h-[200px] h-[43vh] w-full object-cover md:rounded-xl md:h-[40rem]" src="{{ config('app.image_url') }}{{ substr($event['largeImagePath'], 0, -4) }}jpg" alt="{{ $event['name'] }} Immersive Event">
    </picture>
</header>