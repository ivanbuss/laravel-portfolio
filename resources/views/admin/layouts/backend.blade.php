<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Admin Panel for Portfolio52 portal</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="{{ url('panel/css/font-awesome.min.css') }}">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url('panel/css/bootstrap.min.css') }}">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="{{ url('panel/css/dataTables.bootstrap.min.css') }}">
    <!-- Bootstrap social button library -->
    <link rel="stylesheet" href="{{ url('panel/css/bootstrap-social.css') }}">
    <!-- Bootstrap select -->
    <link rel="stylesheet" href="{{ url('panel/css/bootstrap-select.css') }}">
    <!-- Bootstrap file input -->
    <link rel="stylesheet" href="{{ url('panel/css/fileinput.min.css') }}">
    <!-- Awesome Bootstrap checkbox -->
    <link rel="stylesheet" href="{{ url('panel/css/awesome-bootstrap-checkbox.css') }}">
    <!-- Admin Stye -->
    <link rel="stylesheet" href="{{ url('panel/css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{ url('panel/tinymce/tinymce.min.js') }}"></script>

</head>

<body>
@include('admin.layouts.backend-header')

<div class="ts-main-content">
    @include('admin.layouts.backend-leftside')
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Loading Scripts -->
<script src="{{ url('panel/js/jquery.min.js') }}"></script>
<script src="{{ url('panel/js/bootstrap-select.min.js') }}"></script>
<script src="{{ url('panel/js/bootstrap.min.js') }}"></script>
<script src="{{ url('panel/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('panel/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ url('panel/js/Chart.min.js') }}"></script>
<script src="{{ url('panel/js/fileinput.js') }}"></script>
<script src="{{ url('panel/js/main.js') }}"></script>

</body>

</html>