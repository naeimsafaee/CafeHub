@extends('index')

@section('content')
    <div class="mainBody">

        @include('nav')

        @yield('master-content')

        @yield('left')

    </div>

@endsection

