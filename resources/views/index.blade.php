<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ setting('site.title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.fa.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/master.css') }}?x=5"/>
    <link rel="stylesheet" href="{{asset('assets/css/modal.css')}}"/>


    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="194x194" href="{{asset('assets/favicon/favicon-194x194.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/favicon/android-chrome-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('assets/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#fc8e0a">
    <meta name="msapplication-TileImage" content="{{asset('assets/favicon/mstile-144x144.png')}}">
    <meta name="theme-color" content="#fc8e0a">


    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="enamad" content="659080"/>
    @yield('css')

    {!! \Anhskohbo\NoCaptcha\Facades\NoCaptcha::renderJs() !!}

</head>
<body>

@php
    if (auth()->guard('clients')->check()) {
        $client_id = auth()->guard('clients')->user()->id;

        $carts = \App\Models\Cart::query()->where('client_id', $client_id)->get();

        foreach($carts as $cart){
            if($cart->item && !$cart->item->is_available)
                $cart->delete();
        }

        $carts = \App\Models\Cart::query()->where('client_id', $client_id)->get();
    } else {
        $carts = collect();
    }
@endphp

@if($carts->count() > 0)
    <div class="modal show fade pr-0 show_under_1200" id="shoppingBagC" aria-modal="true" onclick="close_cart()">
        <div class="modal-dialog" onclick="show_cart()">
            <div class="modal-content mx-auto h-auto">
                <div class="modal-body row">
                    <div class="col-6">
                        <div class="d-flex  align-content-center">
                            <div class="listOrderIcon">
                                <img src="{{ asset('assets/photo/list order.svg') }}" alt="">

                                <div class="circle"></div>
                            </div>

                            <h6 class="my-auto">لیست سفارش</h6>
                        </div>
                    </div>

                    <div class="col-6 ltr d-flex">
                        @foreach($carts as $cart)
                            @if($loop->index >= 3)
                                @break
                            @endif
                            <div class="imgShppingBag">
                                <img src="{{ Voyager::image($cart->item->image) }}" alt="">
                            </div>
                        @endforeach
                        @if($carts->count() >= 3)
                            <div class="imgShppingBag dFlex">
                                <span>+{{ fa_number($carts->count() - 3) }}</span>
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endif

@yield('content')


{{--    product modal--}}
<div class="modal  fade pr-0" id="shoppingBagO">
    <div class="modal-dialog  dialog-centered">
        <div class="modal-content mx-auto">
            <div class="modal-body dFlex">
                <button class="close btn" type="button" data-dismiss="modal">
                    <img src="{{ asset('assets/photo/close.svg') }}" alt="">
                </button>

                <div class="cards">

                    @foreach($carts as $cart)

                        <div class="cardOrder row">
                            <div class="row cardOrderIn">
                                <img src="{{ Voyager::image($cart->item->image) }}" class="cardOrderImg" alt="">
                                <div class="my-auto">
                                    <h6>{{ $cart->item->name }}</h6>
                                    <div class="details d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="{{asset('assets/photo/ok.svg')}}" alt=""/>
                                            {{ fa_number($cart->item->rate) }}%
                                        </div>
                                        <div>{{ fa_number(number_format($cart->item->price)) }} تومان</div>
                                    </div>
                                </div>
                            </div>

                            <div class="number mx-auto dFlex">
                                <span>{{ fa_number($cart->count) }}</span>
                                <span>عدد</span>

                                <a href="{{ route('delete_cart' , $cart->item->id) }}">
                                    <button class="delete btn" id="Decrease" type="button">
                                        <img src="{{asset('assets/photo/delete.svg')}}" alt="">
                                    </button>
                                </a>

                                <a href="{{ route('add_to_cart' , $cart->item->id) }}">
                                    <button class="plus btn" id="Increase" type="button">
                                        <img src="{{asset('assets/photo/plus.svg')}}" alt="">
                                    </button>
                                </a>
                            </div>
                        </div>

                    @endforeach

                    <hr class="mx-auto">

                    <div class="df dFlex">
                        <div>جمع سفارش</div>
                        <div class="moneyPlus">{{ fa_number(number_format($carts->sum('amount'))) }} تومان</div>
                    </div>
                </div>

                <a href="{{ route('send') }}">
                    <div class="btns">
                        <button class="btn modalBtn" type="button">ثبت سفارش</button>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}?x=10"></script>


<script>

    $(document).ready(function () {

        $(".active_nav").on('click', function (event) {
            $(".rightBody a div").removeClass("activeMenu").removeClass("d-block");

            $(this).find("div").addClass('activeMenu');
            $(this).find("div").addClass('d-block');

        })

    });

</script>

<script>
    /* window.addEventListener('load',

      , false)
*/

    function show_cart() {
        if (window.innerWidth < 1200) {
            $('#shoppingBagO').modal({
                show: true
            });
        }
    }

    function close_cart() {
        /*$('#shoppingBagC').css({
            display: 'none'
        });*/
        /*$("body").removeClass("modal-open");
        $(".modal-backdrop").removeClass("show");*/
    }

    // $('#cartModal').modal('show');
</script>

<script>
    $(document).ready(function () {

        $("body").removeClass("modal-open");
        $(".modal-backdrop").removeClass("show");
    })

</script>

</body>
</html>
