<!DOCTYPE html>

<html>

<head>

    <title>Out of Stock</title>

</head>

<body>

    <h3>Low Stock</h3>
    <br>

    <p>Your product "{{ $prod_name }}" quantity is less then 10 please order it</p>

    <p>Thank you</p>

    <p>Please Visit {{env('APP_URL')}} to login</p>

</body>

</html>