<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    @vite('resources/css/app.css');
</head>

<body>
    <div id="app">
        {{-- <thesis-card></thesis-card> --}}
        {{-- <Dashboard-test :data = "{{ $theses->toJson()}}"></Dashboard-test> --}}
        {{-- <dashboard-test :data = "{{ json_encode($data) }}"></dashboard-test>> --}}
        <?php
        echo '<script>console.log("Data in Blade view:", ' . json_encode($data) . ')</script>';
        ?>

        <script>
            window.dashboardData = @json($dashboardData);
        </script>
    {{-- <router-view :data="{{ json_encode($data) }}"></router-view> --}}
    <router-view></router-view>

</div>

    @vite('resources/js/app.js');

    <h6> BACK TO BLADEEEEEE </h6>


{{-- <script type="module" src="{{ mix('resources/js/app.js') }}"> --}}
    // Correct import statement for Axios
    // import axios from 'axios';
    import Dashboard from './components/Dashboardtest.vue';

    export default {
        components: {
            Dashboard,
        },
        data() {
            return {
                data: [],
            };
        },
        mounted() {
            console.log("Here is Dashboard Test");
            console.log(this.data);

            axios.get('/testvue')
                .then(response => {
                    this.data = response.data;
                });
            },
    };
    </script>
</body>

</html>