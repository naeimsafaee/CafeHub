@extends('auth.index')

@section('content')

    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="pay w-75 mx-auto mb-5">
                    <h6 class="w-100 text-center">مبلغ قابل پرداخت</h6>
                    <div class="money text-center">{{ fa_number(number_format($carts->sum('amount'))) }} تومان</div>

                    <div class="d-flex justify-content-center mx-auto" style="flex-wrap: wrap; width: 306px;">
                        {{--<div class="methodPay mx-lg-2">

                            <a href="{{ route('success') }}" class="dFlex">در کافه هستم پرداخت میکنم</a>

                        </div>--}}
                        <div class="methodPay mx-lg-2 mt-3">

                            <a href="{{ route('pay_type' , ["type" => 1]) }}" class="dFlex">
                                تحویل حضوری در کافه
                            </a>

                        </div>

                        @if(setting('site.send_cafe') == 1 || true)
                            <div class="methodPay mx-lg-2 mt-3">
                                {{-- href="{{ route('address') }}"--}}
                                <a class="dFlex" style="background: #39393970;">ارسال به محل شما</a>

                            </div>
                        @endif

                    </div>


                </div>
            </div>


        </div>

    </div>
@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection


