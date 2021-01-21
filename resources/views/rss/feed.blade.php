<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ Everything Immersive ]]></title>
        <link><![CDATA[ https://everythingimmersive.com/feed ]]></link>
        <description><![CDATA[ Your resource for immersive and interactive theatre, art, virtual reality, escape rooms, dance and more. ]]></description>
        <language>en</language>
        <pubDate>{{ now() }}</pubDate>

        @foreach($events as $event)
            <item>
                <title><![CDATA[{{ $event->name }}]]></title>
                <link>{{Request::root()}}/events/{{ $event->slug }}</link>
                <dates>
                    @foreach($event->shows as $show)
                        {{ $loop->first ? '' : ',' }}
                        {{ date('M d Y', strtotime($show->date)) }}
                    @endforeach
                </dates>
                <platform>
                    @foreach($event->remotelocations as $platform)
                        {{ $loop->first ? '' : ',' }}
                        {{ $platform->name }}
                    @endforeach
                </platform>
                <price>{{ $event->price_range }}</price>
                <age>{{ $event->age_limits->name }}</age>
                <category>{{ $event->category->name }}</category>
                <description><![CDATA[{!! $event->description !!}]]></description>
                <author><![CDATA[{{ $event->organizer->name  }}]]></author>
                <guid>{{ $event->id }}</guid>
                <pubDate>{{ $event->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>