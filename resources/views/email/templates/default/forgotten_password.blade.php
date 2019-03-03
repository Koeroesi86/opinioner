<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Forgotten password</h2>

        <div>
            Please follow the link below to set a new password 
            {{ URL::to("forgotten-password/{$emailAddress}/{$forgottenCode}") }}.<br />

        </div>

    </body>
</html>