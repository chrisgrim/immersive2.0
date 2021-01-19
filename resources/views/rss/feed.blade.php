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
                <description><![CDATA[{!! $event->description !!}]]></description>
                <category>{{ $event->category->name }}</category>
                <author><![CDATA[{{ $event->organizer->name  }}]]></author>
                <guid>{{ $event->id }}</guid>
                <pubDate>{{ $event->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>