		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="/storage/website-files/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/storage/website-files/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/storage/website-files/favicons/favicon-16x16.png">
        <link rel="manifest" href="/storage/website-files/favicons/site.webmanifest">
        <link rel="mask-icon" href="/storage/website-files/favicons/safari-pinned-tab.svg" color="#f7653b">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#f7653b">
        @yield('head')
        <script src="{{ mix('/assets/manifest.js') }}" defer></script>
        <script src="{{ mix('/assets/vendor.js') }}" defer></script>
        <script src="{{ mix('/assets/app.js') }}" defer></script>
        <link
            rel="preload"
            href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&display=swap"
            as="style"
            onload="this.onload=null;this.rel='stylesheet'"
        />
        <noscript>
            <link
                href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&display=swap"
                rel="stylesheet"
                type="text/css"
            />
        </noscript>
        <script async src="https://www.googletagmanager.com/gtag/js?id={{Config::get('services.analytics.id')}}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '{{Config::get('services.analytics.id')}}');
        </script>


