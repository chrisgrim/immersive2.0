<section class="py-12 px-8 md:py-16 md:px-0">
    <div>
        <div class="title first">
            <h3 class="font-semibold md:font-medium">Audience Role</h3>
        </div>     
        <div class="my-8">
            <p class="relative pb-12 overflow-auto" style="white-space: pre-line;">
                {{ implode(' ', array_slice(explode(' ', $event->advisories['audience']), 0, 30)) }} <show-more text="{{ $event->advisories['audience']}}" :limit="30" />
            </p>
        </div> 
    </div>
    <div>
        <div>
            <h3 class="font-semibold md:font-medium">Content Advisories</h3>
        </div>
        <ul class="my-8 list-disc">
            @foreach($event->contentAdvisories as $item)
                <li>
                    <p>{{ $item['advisories'] }}</p>
                </li>
            @endforeach
            <li>
                <p> {{ $event->age_limits ? $event->age_limits['name'] : $event->advisories['ageRestriction'] }} </p>
            </li>
        </ul>
    </div>

    <div>
        <div>
            <h3 class="font-semibold md:font-medium">Interaction Advisories</h3>
        </div>
        <ul class="my-8 list-disc">
            @foreach($event->contactLevels as $item)
                <li>
                    <p> {{ $item['level'] }} </p>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <div>
            <h3 class="font-semibold md:font-medium">Mobility Advisories</h3>
        </div>
        <ul class="my-8 list-disc">
            @foreach($event->mobilityAdvisories as $item)
                <li>
                    <p> {{ $item['mobilities'] }} </p>
                </li>
            @endforeach
            <li>
                <p>Event is <span>@if(!$event->advisories['wheelchairReady']) not @endif</span> wheelchair accessible.</p>
            </li>
        </ul>
    </div>

    @if($event->advisories['sexual'])
        <div class="title">
            <h3 class="mb-4">Sexual Advisories</h3>
        </div>
        <ul class="info">
            <li>
                <p> {{ $event->advisories['sexualDescription'] }} </p>
            </li>
        </ul>
    @endif

    <div>
        <h3 class="mb-4">Tags</h3>
        @foreach($event->genres as $item)                
            <div class="inline-block rounded-md border border-slate-200 mr-4 mt-4 px-4 {{ $item['admin'] == 1 ? 'cursor-pointer hover:bg-gray-400 hover:text-white hover:border-white' : '' }}">
                @if($item['admin'] == 1)
                    <a href="/index/search?tag={{ $item['id'] }}&searchType=allEvents"><b><span> {{ $item['name'] }} </span></b></a>
                @else
                    <span>{{ $item['name'] }}</span>
                @endif
            </div>
        @endforeach
    </div>
</section>