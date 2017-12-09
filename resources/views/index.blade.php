<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{asset('vendor/parameters/css/core.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/parameters/css/app.css')}}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Paraman = <?php echo json_encode(Paraman\ParametersManager::clientData()); ?>;

    </script>

</head>
<body>
    <div class="container-fluid" id="app">
        <paraman :parameters-list='{!! $parameters->toJson(JSON_HEX_APOS) !!}'></paraman>
    </div>
<script src="{{asset('vendor/parameters/js/core.js') . '?v='.Illuminate\Support\Str::random()}}"></script>
<script src="{{asset('vendor/parameters/js/app.js') . '?v='.Illuminate\Support\Str::random()}}"></script>
</body>
</html>