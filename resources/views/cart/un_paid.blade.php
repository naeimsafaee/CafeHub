@extends('auth.index')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-12 justify-content-center">
                <div class="unsuccessful dFlex mx-auto">
                    <img src="{{ asset('assets/photo/unsuccessful.svg') }}"
                         class="imgSuccess"
                         alt=""/>

                    <h4 class="w-100">عملیات پرداخت ناموفق بود</h4>
                    <div class="error">مشکلی در پرداخت پیش امده لطفا دوباره تلاش کنید</div>

{{--                    <button type="submit" class="btn">تلاش مجدد و پرداخت</button>--}}
                </div>
            </div>


        </div>
    </div>

@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection
