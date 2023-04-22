<section class="border-b border-slate-200 py-12 px-8 md:py-8 md:px-0">
    <div class="flex w-full p-4 pb-8">
        <picture>
            <source 
                type="image/webp" 
                srcset="{{ config('app.image_url') . $event->staffpick->user->thumbImagePath }}"> 
            <img 
                class="w-20 h-20 rounded-full mr-4" 
                src="{{ config('app.image_url') . $event->staffpick->user->thumbImagePath . '.jpg' }}" 
                alt="{{ $event->staffpick->user->name . ' Immersive Event' }}">
        </picture>
        <div>
            <h3>Staff Pick</h3>
            <i><p class="text-2xl text-gray-500">-{{ $event->staffpick->user->name }}</p></i>
        </div>
    </div>
    <div class="">
        <p class="relative" style="white-space: pre-wrap;font-style: italic;">{{ implode(' ', array_slice(explode(' ', $event->staffpick->comments ), 0, 100)) }} <show-more text="{{ $event->staffpick->comments }}" :limit="100" :italic="true"/>
        </p>
    </div>
</section>
