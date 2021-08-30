<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('sage_pay/style/main.css') }}">
    <title>Error</title>
</head>
<body>
<div class="container">
    <img src="{{ asset('sage_pay/img/not_found.svg') }}" alt="error">
    <p style="font-size: 1.9rem">{{ $error }}</p>

    <div class="powered_by">
        <img src="{{ asset('sage_pay/img/padlock.svg') }}" alt="padlock">
        <h3>Secure Payment By</h3>
        <img src="{{ asset('sage_pay/img/capiflex.jpg') }}" width="60" alt="sagecloud" class="sc_logo">
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>
</html>
