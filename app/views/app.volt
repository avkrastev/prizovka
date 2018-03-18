<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Default theme - jQuery Mobile Demos</title>
  <link rel="stylesheet" href="{{ static_url('css/jquery.mobile-1.4.5.min.css') }}" type="text/css" />
  <!-- Google fonts - Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  <link rel="stylesheet" href="{{ static_url('css/app.css') }}" type="text/css" />
	<link rel="shortcut icon" href="../favicon.ico">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUWhWLcBeYctXEvOdzOHOQcpvfKDbVZsQ&libraries=places&language=bg&region=BG" async defer></script>
  {{ javascript_include('js/jquery.mobile-1.4.5.min.js') }}
  {{ javascript_include('js/app.js') }}
</head>
<body>
  {{ content() }}
</body>
</html>
