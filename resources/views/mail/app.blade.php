<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/mail.css?v=' . time()) }}">
</head>

<body>

    <table class="w-100 mail-container">
        <tr>
            <td>@yield('content')</td>
        </tr>
    </table>

</body>

</html>