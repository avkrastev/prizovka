<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{ get_title() }}
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ static_url('css/bootstrap.min.css') }}" type="text/css" />
    <!-- Bootstrap Datepicker CSS-->
    <link rel="stylesheet" href="{{ static_url('css/bootstrap-datepicker.min.css') }}" type="text/css" />
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ static_url('css/style.default.css') }}" type="text/css" />
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{ static_url('css/grasp_mobile_progress_circle-1.0.0.min.css') }}" type="text/css" />
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ static_url('css/custom.css') }}" type="text/css" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ static_url('img/favicon.ico') }}">
    <!-- Font Awesome CDN-->
    <!-- you can replace it by local Font Awesome-->
    <script src="https://use.fontawesome.com/99347ac47f.js"></script>
    <!-- Font Icons CSS-->
    <!-- <link rel="stylesheet" href="https://file.myfontastic.com/da58YPMQ7U5HY8Rb6UxkNf/icons.css">-->
    <link rel="stylesheet" href="{{ static_url('css/ionicons.css') }}" type="text/css" />
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    {{ content() }}
    <!-- Javascript files-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtCnmwX45uhYbzCjNI7a5FRl4PbthO2LU&libraries=places&language=bg&region=BG" async defer></script>
    {{ javascript_include('js/tether.min.js') }}
    {{ javascript_include('js/bootstrap.min.js') }}
    {{ javascript_include('js/bootstrap-datepicker.min.js') }}
    {{ javascript_include('locales/bootstrap-datepicker.bg.min.js') }}
    {{ javascript_include('js/jquery.cookie.js') }}
    {{ javascript_include('js/grasp_mobile_progress_circle-1.0.0.min.js') }}
    {{ javascript_include('js/jquery.nicescroll.min.js') }}
    {{ javascript_include('js/jquery.validate.min.js') }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    {{ javascript_include('js/front.js') }}
    {{ javascript_include('js/base64.js') }}
    {{ javascript_include('js/custom.js') }}
    {{ javascript_include('js/jquery.simplePagination.js') }}
  </body>
</html>
