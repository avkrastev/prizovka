<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{ get_title() }}
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ static_url('css/bootstrap.min.css') }}" type="text/css" />
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ static_url('css/style.default.css') }}" type="text/css" />
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{ static_url('css/grasp_mobile_progress_circle-1.0.0.min.css') }}" type="text/css" />
    <!-- Custom stylesheet - for your changes-->s
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
    {{ javascript_include('js/tether.min.js') }}
    {{ javascript_include('js/bootstrap.min.js') }}
    {{ javascript_include('js/jquery.cookie.js') }}
    {{ javascript_include('js/grasp_mobile_progress_circle-1.0.0.min.js') }}
    {{ javascript_include('js/jquery.nicescroll.min.js') }}
    {{ javascript_include('js/jquery.validate.min.js') }}
    {{ javascript_include('js/front.js') }}
    {{ javascript_include('js/custom.js') }}
  </body>
</html>
