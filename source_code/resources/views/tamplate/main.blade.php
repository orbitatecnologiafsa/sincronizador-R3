<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo')</title>
    <link rel="stylesheet" href=" {{ asset('css/bootstrap.min.css') }}">
</head>

<body>

   @include('tamplate.nav')
    <div class="container">
        @yield('conteudo')
    </div>

    {{-- <script src="{{ asset('js/bootstrap.bundle.js') }}"></script> --}}
    <script src="{{ asset('js/bootstrap.js') }}"></script>
</body>

</html>
