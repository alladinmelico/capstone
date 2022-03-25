<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <style>
            body,
            html {
                height: 100%;
            }
            main {
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: auto;
                margin-bottom: auto;
            }
            #card {
                margin: auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                max-width: 300px;
                padding: 2rem;
                border-radius: 0.5rem;
                border: 1 solid #414242;
            }

            h1 {
                padding: 0.5rem;
                border-radius: 0.5rem;
                color: white;
            }

            #valid {
                background-color: #00838f;
                text-align: center;
                margin-left: 1rem;
            }

            #not-valid {
                background-color: #8f0000;
                text-align: center;
                margin-left: 1rem;
            }
        </style>
    </head>
    <body>
        <main>
            <div id="card">
                <img src="/logo.svg" alt="ssc logo" height="100">
                <div>
                    @if ($isValid)
                        <h1 id="valid">Valid Schedule</h1>
                    @else
                        <h1 id="not-valid">Not Valid Schedule</h1>
                    @endif
                </div>
            </div>
        </main>
    </body>
</html>
