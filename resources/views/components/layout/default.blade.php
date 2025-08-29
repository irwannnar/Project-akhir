<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>iMon Jasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-300">
    <x-header />
    <x-navbar />
    {{ $slot }}
    <x-footer />
</body>

</html>
