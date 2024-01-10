<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}">

	@vite(['resources/js/assets/app.css', 'resources/js/main.ts'])
</head>

<body>
	<div id="app"></div>
</body>

</html>
