<!DOCTYPE html>

<html>

<head>

    <title>Welcome to Pos</title>

</head>

<body>

    <h3>Welcome to POS Mr/Mrs  <b>{{$name}}</b></h3>
    <br>

    <p>Your Password is {{ $password }}</p>

    <p>Thank you</p>

    <p>Please Visit {{env('APP_URL')}} to login</p>

</body>

</html>