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

                    <h4 class="w-100">سفارش با موفقیت ثبت شد</h4>

                    @if($transaction ?? false)

                        @if(!$transaction->paid)

                            <div>
                                <span>
                                مبلغ قابل پرداخت به صورت حضوری به کافه : {{ fa_number(number_format($transaction->amount)) }}
                                </span>
                                @if($transaction->wallet_amount > 0)
                                    <span>
                                        -
                                    مبلغ پرداخت شده با کیف پول : {{ fa_number(number_format($transaction->wallet_amount)) }}
                                    </span>
                                @endif
                            </div>

                        @else
                            <h4 class="w-100">پرداختی کامل انجام شده است.</h4>

                            @if($transaction->wallet_amount > 0)
                                <span>
                                        -
                                    مبلغ پرداخت شده با کیف پول : {{ fa_number(number_format($transaction->wallet_amount)) }}
                                    </span>
                            @endif

                            <div class="w-100">کد پیگیری :  {{ $auth }}</div>

                        @endif


                    @endif


                </div>
            </div>

            <div class="col-12 mb-5">
                @foreach($orig_carts as $cart)

                    <div class="ticket d-flex align-content-center align-items-center mx-auto">

                        <div class="right dFlex">
                            <span>{{ fa_number($cart->count) }}</span>
                            <span>عدد</span>
                        </div>

                        <div class="left w-100">
                            <div class="name">{{ $cart->item->name }}</div>
                            <div class="row ">
                                <div class="col-6">
                                    <img src="{{asset('assets/photo/ok.svg')}}" class="ok" alt=""/>

                                    <span class="percent"> {{ fa_number($cart->item->rate) }}%</span>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="price">{{ fa_number(number_format($cart->item->price * $cart->count)) }}
                                        تومان
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection
