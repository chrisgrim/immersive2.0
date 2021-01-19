<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    @foreach ($organizers as $organizer)
        <url>
            <loc>
                {{ asset('/organizer/' . $organizer->slug)}}
            </loc>
            <lastmod>
                {{$organizer->created_at->format('Y-m-d')}}
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