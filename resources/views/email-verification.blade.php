<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title>{{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900" rel="stylesheet">

    <style>

        body {
            font-family: Roboto, sans-serif;
            margin: 0;
            height: 100vh;
            width: 100%;
        }

        main {
            height: 100%;
        }
        
        .wrapper {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content {
            max-width: 600px;
            min-width: 300px;
            padding: 3rem;
            background-color: whitesmoke;
        }

        h1 {
            font-weight: 900;
            text-transform: uppercase;
        }

        p {
            margin-bottom: 0;
        }

    </style>
</head>

<body>

<main>

    <div class="wrapper">

        <div class="content">

            <h1>{{ __("Email verified") }}</h1>
            <h2>{{ env('APP_NAME') }}</h2>

            <p><strong>{{ __("Your email is now verified.") }}</strong> {{ __("You can close this window now.") }}</p>

        </div>

    </div>

</main>

</body>
</html>
