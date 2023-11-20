<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    @vite('resources/css/app.css');
</head>
<body>
    <div id="app">

        {{-- <router-view :server-data="{{ json_encode($data) }}"></router-view> --}}
        <component-a></component-a>
    </div>
    @vite('resources/js/app.js');

    <script type="module" src="{{ mix('resources/js/app.js') }}">
    </script>
</body>
</html>

