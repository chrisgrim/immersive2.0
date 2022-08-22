<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    @foreach ($events as $event)
        <url>
            <loc>
                {{ asset('/events/' . $event->slug)}}
            </loc>
            <image:image>
                <image:loc>{{ env('MIX_IMAGE_URL') }}{{ $event->largeImagePath }}</image:loc>
                <image:title> {{$event->name}} </image:title>
                <image:caption> {{$event->tag_line ? \Illuminate\Support\Str::limit($event->tag_line, 80) : \Illuminate\Support\Str::limit($event->description, 80)}} </image:caption>
            </image:image>
            <lastmod>
                {{$event->created_at->format('Y-m-d')}}
            </lastmod>
            <changefreq>
                weekly
            </changefreq>
            <priority>
                .5
            </priority>
        </url>
    @endforeach
 </urlset>