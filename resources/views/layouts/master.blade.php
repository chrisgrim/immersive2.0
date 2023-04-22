<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
        @yield('meta')
		@include('layouts.meta')
	</head>
	<body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5LHWVRN"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <header style="height:8rem;" id="header">
            @yield('nav')
        </header>
		<main>
			@yield('content')
            @yield('content2')
		</main>
		@include('layouts.footer')
	</body>
</html>