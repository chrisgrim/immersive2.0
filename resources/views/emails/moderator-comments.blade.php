<link href="https://fonts.googleapis.com/css?family=Sen&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Secular+One|Sen&display=swap" rel="stylesheet">

<body style="margin:0!important">
    <div style="border: 1px solid #dddddd; border-radius: 12px;overflow: hidden;max-width: 1000px;margin:40px auto">
        <div style="height:375px;">
            <img style="width:100%;height:100%;object-fit:cover;" src="{{ env('MIX_IMAGE_URL') }}{{substr($image, 0,-4)}}jpg" alt="Image of the {{$name}} event">
        </div>
        <div style="margin: auto;padding:40px 20px;">
            <div style="margin-bottom: 10px;">
                <h2 style="font-family: 'Secular One', sans-serif;font-size: 30px;margin:0">We have some notes!</h2>
            </div>
            <div style="margin-bottom: 35px;">
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p>Your event {{$name}} on Everything Immersive has been reviewed by a moderator and has some changes. Please see below and resubmit.</p>
                </span>
            </div>
            <div>
                <h4 style="font-family: 'Secular One', sans-serif;font-size: 15px;margin:0">Notes:</h4>
            </div>
            <div>
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p style="font-size: 14px;">{{$comments}}</p>
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