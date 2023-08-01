@extends('auth.index')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pay mx-auto">
                    <h6 class="w-100 text-center">مبلغ کل قابل پرداخت</h6>

                    <div class="money text-center" id="wallet">{{ fa_number(number_format($all_price)) }} تومان</div>

                    <div class="money text-center" id="all_price"
                         style="display: block">{{ fa_number(number_format($price)) }} تومان با کیف پول
                    </div>

                    <div class="d-flex justify-content-between" style="flex-wrap: wrap;">
                        <div class="methodPay">
                            <a {{--href="{{ route('pay') }}"--}} style="background: #39393970;"
                               class="dFlex">
                                پرداخت آنلاین
                            </a>
                        </div>

                        <div class="methodPay mt-3 mt-md-0">
                            <a href="{{ route('pay' , ['use_wallet' => auth()->guard('clients')->user()->wallet > 0]) }}"
                               class="dFlex">
                                <div class="d-block w-100 text-center">
                                    موجودی {{ fa_number(number_format(auth()->guard('clients')->user()->wallet)) }}
                                    تومان
                                </div>
                                <div class="d-block w-100 text-center">پرداخت با کیف پول</div>
                            </a>
                        </div>
                    </div>


                    {{--                    <button type="submit" class="btn mx-auto">تایید و پرداخت</button>--}}
                </div>
            </div>


        </div>

    </div>

@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection


