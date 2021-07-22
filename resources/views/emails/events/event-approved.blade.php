<link href="https://fonts.googleapis.com/css?family=Sen&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Secular+One|Sen&display=swap" rel="stylesheet">

<body style="margin:0!important">
    <div style="border: 1px solid #dddddd; border-radius: 12px;overflow: hidden;max-width: 1000px;margin:40px auto">
        <div style="height:375px;">
            <img style="width:100%;height:100%;object-fit:cover;" src="{{URL::to('/')}}/storage/{{substr($image, 0,-4)}}jpg" alt="Image of the {{$name}} event">
        </div>
        <div style="margin: auto;padding:40px 20px;">
            <div style="margin-bottom: 10px;">
                <h2 style="font-family: 'Secular One', sans-serif;font-size: 30px;margin:0">Approved!</h2>
            </div>
            <div style="margin-bottom: 35px;">
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p>We are happy to let you know that <a href="{{URL::to('/')}}/events/{{$slug}}">{{$name}}</a> has been approved and is live on Everything Immersive (unless you specified an embargo date).</p>
                </span>
            </div>
            <div>
                <h4 style="font-family: 'Secular One', sans-serif;font-size: 15px;margin:0">Did you know...</h4>
            </div>
            <div>
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p style="font-size: 14px;">You can change your dates, ticket prices, and almost all event information whenever you like without having to resubmit! Use the "Your Events" link in the nav bar to see a list of all of your events or <a href="{{URL::to('/')}}/create/events/edit">click here.</a></p>
                </span>
            </div>
            <div style="border-top: 1px solid #dddddd!important;padding: 0px!important;margin: 30px 0;">
            </div>
            <div>
                <h2 style="font-family: 'Secular One', sans-serif;font-size: 35px;margin:0">EI.</h2>
            </div>
            <div>
                <a href="{{URL::to('/')}}" style="font-family: 'Secular One', sans-serif;font-size: 15px;">{{URL::to('/')}}.</a>
            </div>
        </div>
    </div>
</body>