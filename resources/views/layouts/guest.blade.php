<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> {{trans('login.title')}}</title>
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>--}}
    @vite(['resources/css/app.css','resources/scss/app.scss','resources/js/main.js'])
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body class="gray-bg">

{{ $slot }}

</body>

</html>








