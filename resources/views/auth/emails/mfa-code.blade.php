<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1>Your Two-Factor Authentication Code</h1>
                    <p>Hello,</p>
                    <p>Your MFA code is: <strong>{{ $mfaCode }}</strong></p>
                    <p>This code will expire in 10 minutes. Please use it to complete your login process.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>