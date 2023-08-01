@extends('index')

@section('content')

    <div class="container-fluid">

        @include('small_nav')
        <!-- 404  -->
        <div class="row mx-auto">
            <div class="col-12">

                <div class="notFind mx-auto">
                    <div class="notFindImg">
                        <img src="{{ asset('assets/photo/404.svg') }}" class="" alt="">

                        <img src="{{ asset('assets/photo/404 line.svg') }}" class="line" alt="">
                    </div>

                    <h6 class="text-center">صفحه ای یافت نشد</h6>
                    <p class="text-center">متاسفانه صفحه مورد نظر شما یافت نشد</p>

                    <button class="btn mx-auto" type="button">بازگشت به صفحه اصلی</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}"/>

@endsection
