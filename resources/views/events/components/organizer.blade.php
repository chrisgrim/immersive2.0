<section 
    id="organizer" 
    class="py-12 px-8 md:py-16 md:px-0">  
    <a href="/organizer/{{ $event->organizer->slug }}">
        <div class="flex mb-8 gap-4">
            <div 
                style="{{ $event->organizer->thumbImagePath ? 'background-image:url(' . config('app.image_url') . substr($event->organizer->thumbImagePath, 0, -4) . 'jpg)' : 'background:' . $event->organizer->hexColor }};"
                class="mr-4 w-20 h-20 rounded-full flex justify-center items-end border bg-no-repeat bg-cover overflow-hidden flex-shrink-0">
                @if(!$event->organizer->thumbImagePath)
                    <p class="text-5xl text-white font-extrabold m-auto">{{ substr($event->organizer->name, 0, 1) }}</p>
                @endif
            </div>
            <div>
                <h3> {{ $event->organizer->name }} </h3>
                <p class="text-2xl md:text-xl text-gray-500"> Hosting immersive events on EI since {{ \Carbon\Carbon::parse($event->organizer->created_at)->format('F, Y') }}</p>
            </div>
        </div>
    </a>
    <p class="relative overflow-auto" style="white-space: pre-wrap;">{{ implode(' ', array_slice(explode(' ', $event->organizer->description), 0, 100)) }} <show-more text="{{ $event->organizer->description }}" :limit="100" />
    </p>
</section> 