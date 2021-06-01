
<link href="https://fonts.googleapis.com/css?family=Sen&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Secular+One|Sen&display=swap" rel="stylesheet">

<body style="margin:0;margin-top: 5%;">
    <div>
        <div style="max-width: 550px;margin: auto;padding: 0rem 1rem;">
            <div style="text-align: center;">
                <h2 style="font-family: 'Secular One', sans-serif;font-size: 3rem;">EI.</h2>
            </div>
            <div style="text-align: center;margin-bottom: 1.4rem;">
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p>There are 72 hours until the last showing of {{$event->name}} on Everything Immersive</p>
                </span>
                <span style="font-family: 'Sen', sans-serif;color: #6d6d6d;">
                    <p>Head to the shows page to add more dates or renew your on going events and keep your content live on EI.
                </span>
            </div>
        </div>
        <div style="
            width: 40%;
            margin: auto;
            ">
            <div style="text-align: center;max-width: 650px;margin: auto;padding: 0rem 1rem;">
                <img style="width:100%;border-radius:.3rem;" src="https://secretchickens.com/storage/{{$event->thumbImagePath}}" alt="">
            </div>
        </div>
        <div style="text-align: center;max-width: 550px;margin: auto;padding: 2rem 1rem;">
            <a href="https://secretchickens.com/create/{{$event->slug}}/shows">
                <button style="border: 1px solid #1abdb6;padding: .5rem 1rem;font-size: 1rem;color:#0c8a84;">Renew your event</button>
            </a>
        </div>
            </div>
        </div>
    </div>
</body>