<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
    </script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

    <title>Title</title>
</head>
<body>
    <div id="app">
        <app></app>
    </div>

    <script src="../js/app.js"></script>

    <script>
        // channels for testing purposes

        Echo.channel('development-idea').notification((e) => {
            console.log('development-idea', e);
        })

        Echo.channel('improvement-idea').notification((e) => {
            console.log('improvement-idea', e);
        })

        Echo.channel('answer-no-found').notification((e) => {
            console.log('answer-no-found', e);
        })

        Echo.channel("report-problem").notification((e) => {
            console.log("report problem", e);
        });

        Echo.channel("user-placed-order").notification((e) => {
            console.log("user-placed-order", e);
        });

        Echo.channel("fetch-notifications").listen('.fetch-notifications', function (e) {
            console.log('fetch-notifications', e);
        })
    </script>
</body>
</html>
