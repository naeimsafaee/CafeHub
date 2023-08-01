<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ setting('site.title') }}</title>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.fa.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/master.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/login.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/profile.css')}}" />
    @yield('css')


</head>
<body>
<!-- nav  -->
<div class="row smallnav">
    <div class="col-6 d-none d-md-block">
        <a href="{{ route('home') }}">
            <img src="{{ Voyager::image(setting("site.logo")) }}" class="logoNav" alt="" />

        </a>
    </div>
    <div class="col-12 col-md-6 text-left">
        <button type="button" class="btnBackNav btn">
            <a  href="{{ route('home') }}">
                <img src="{{ asset('assets/photo/btn back navbar.svg') }}" alt="" />
            </a>
        </button>
    </div>
</div>
@yield('content')
<script src="{{asset('assets/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
</body>
</html>
