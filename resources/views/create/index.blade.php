@extends('layouts.master')

@section('meta')
        <!-- Hotjar Tracking Code for https://everythingimmersive.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:{{Config::get('services.hotjar.id')}},hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
        <link href="{{ mix('/assets/app-create.css') }}" rel="stylesheet">
        <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
        @yield('location')
@endsection

@section('content')
    <div id="create">
    	@yield('created')
    </div>	
@endsection

