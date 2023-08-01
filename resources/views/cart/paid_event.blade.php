@extends('auth.index')
@section('content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-12 justify-content-center">
                <div class="successful dFlex mx-auto">
                    <img
                        src="{{asset('assets/photo/successful.svg')}}"
                        class="imgSuccess"
                        alt=""/>

                    <h4 class="w-100">پرداخت با موفقیت انجام شد</h4>
{{--                    <div class="Vcode">کد تایید {{ $auth }}</div>--}}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection
