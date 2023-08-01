@extends('master')

@section('master-content')

    <div class="middle">
        <!-- nav  -->
        <div class="row w-100 mb-5">
            <div class="col-2"></div>
            <div class="col-8 text-center">
                <div class="lastOrderNav">
                    <img src="{{asset('assets/photo/Past orders.svg')}}" alt=""/>
                    سفارش های گذشته
                </div>

            </div>
            <div class="col-2">
                <a href="{{ route('home') }}">

                    <button type="button" class="btnBackNav btn">
                        <img src="{{asset('assets/photo/btn back navbar.svg')}}" alt=""/>
                    </button>
                </a>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            @foreach($client_item as $key => $value)

                <div class="hamishegi">
                    <div class="d-flex">
                        <div class="clock d-flex">
                            <img
                                src="{{asset('assets/photo/clock hamishegi.svg')}}"
                                class="m-auto"
                                alt=""
                            />
                        </div>
                        <div class="date">{{ fa_number($value->first()->created_at->diffForHumans()) }}</div>
                    </div>

                    <div class="cardOrder cardHamishegi row">
                        @foreach($value as $item)

                            <div class="row cardOrderIn">
                                <img src="{{ Voyager::image($item->item->image) }}"
                                     class="cardOrderImg"
                                     alt=""/>
                                <div class="my-auto">
                                    <div
                                        class="d-flex justify-content-between align-content-center">
                                        <h6>{{ $item->item->name }}</h6>
                                        <div>{{ fa_number($item->count) }} عدد</div>
                                    </div>
                                    <div
                                        class="details d-flex justify-content-between  align-content-center ">
                                        <div class="row align-content-center">
                                            <img src="{{asset('assets/photo/ok.svg')}}" alt=""/>
                                            {{ fa_number($item->item->rate) }}%
                                        </div>
                                        <div>{{ fa_number(number_format($item->item->price * $item->count)) }}تومان
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class=""/>

                    @if(count($value) > 0)

                        @php

                            $total = \App\Models\Transaction::query()->find($value->first()->transaction_id);

                        @endphp
                        @if($total)
                            <div class="df dFlex">
                                <div>جمع سفارش</div>
                                <div class="moneyPlus">{{ fa_number(number_format($value->sum('amount'))) }} تومان</div>
                            </div>

                            <a href="{{ route('order_again' , $value->first()->transaction_id ) }}">
                                <button class="btn mx-auto" type="button">سفارش مجدد</button>
                            </a>
                        @endif

                    @endif


                </div>
            @endforeach
        </div>

    </div>

@endsection


@section('left')

    <div class="leftBody">
        <div class="d-flex  align-content-center" style="margin-bottom: 74px;">
            <div class="listOrderIcon">
                <img src="{{ asset('assets/photo/list order.svg') }}" alt=""/>

                @if($carts->count() > 0)
                    <div class="circle"></div>
                @endif
            </div>

            <h6 class="my-auto">لیست سفارش</h6>
        </div>

        <div id="reserve_cafe_card" style="display: none" class="cards">

            <div class="cardOrder row">

                <div class="reserve mx-auto">
                    <h2>رزور کافه</h2>

                    <div class="reserveIcon mx-auto">
                        <img src="{{ asset('assets/photo/reserve icon.svg') }}" alt="">
                    </div>

                    <div class="">
                        <form method="post" id="main_form_1" action="{{ route('reserve_cafe') }}">
                            @csrf
                            <label for="description">توضیح در مورد رزرو</label>
                            <textarea placeholder="موضوع" class="mx-auto" name="description" id="" cols="30"
                                      rows="10"></textarea>
                            @error('description')
                            <span style="color: red">
                                            {{ $message }}
                                        </span>
                            @enderror

                            <label for="name">نام خود را وارد کنید</label>
                            <input placeholder="نام شما" type="text" name="name" id=""/>
                            @error('name')
                                <span style="color: red">
                                    {{ $message }}
                                </span>
                            @enderror

                            <label for="phone">شماره موبایل خود را وارد کنید</label>
                            <input placeholder="شماره موبایل" type="number" name="phone" id=""/>
                            @error('phone')
                                <span style="color: red">
                                    {{ $message }}
                                </span>
                            @enderror

                            <input type="hidden" id="number_of_re" name="count" value="1"/>

                        </form>
                    </div>

                </div>

                <div class="number mx-auto dFlex">
                    <span id="count_of_reserv_1" name="count">1</span>
                    <span>نفر</span>
                    <button class="delete btn" type="button" onclick="delete_reserv1()">
                        <img src="{{asset('assets/photo/delete.svg')}}" alt="">
                    </button>
                    <button class="plus btn" type="button" onclick="add_reserv1()">
                        <img src="{{asset('assets/photo/plus.svg')}}" alt="">
                    </button>
                </div>

            </div>

            <hr class="mx-auto">

            <button onclick="document.getElementById('main_form_1').submit()"  type="button" class="btn mafiaBtn mx-auto mb-5">تایید و پرداخت</button>
        </div>

        @if(count($reserve_event) > 0)

            <div id="reserve_card" class="cards">

                @foreach($reserve_event as $event)

                    <div class="cardOrder row">

                        <div class="mafiaEventCard">
                            <h2>{{ $event->event->title }}</h2>
                            <div class="subMafia">
                                <div class="">
                                    <img src="{{asset('assets/photo/calendar.svg')}}" alt=""/>
                                    <span class="ml-4">{{ $event->event->date }}</span>
                                </div>

                                <div class="">
                                    <img src="{{asset('assets/photo/clock.svg')}}" alt=""/>
                                    ساعت :
                                    <span>{{ $event->event->hour }}</span>
                                </div>
                            </div>
                            <p>
                                {!! $event->event->description !!}
                            </p>
                        </div>

                        <div class="number mx-auto dFlex">
                            <span>{{ fa_number($event->count) }}</span>
                            <span>عدد</span>
                            <a href="{{ route('delete_event' , $event->event->id) }}">
                                <button class="delete btn" type="button">
                                    <img src="{{asset('assets/photo/delete.svg')}}" alt="">
                                </button>
                            </a>

                            <a href="{{ route('add_to_event' , $event->event->id) }}">

                                <button class="plus btn" type="button">
                                    <img src="{{asset('assets/photo/plus.svg')}}" alt="">
                                </button>
                            </a>
                        </div>
                    </div>
                @endforeach


                <hr class="mx-auto">

                <div class="df dFlex">
                    <div>جمع سفارش</div>
                    <div class="moneyPlus">{{ fa_number(number_format($reserve_event->sum('amount'))) }} تومان</div>
                </div>

                <a href="{{ route('pay_reserve') }}">

                    <button type="button" class="btn mafiaBtn mx-auto">تایید و پرداخت</button>
                </a>
            </div>

        @elseif(count($carts) > 0)
            <div id="cart_card" class="cards">

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

            <div id="cart_card_1" class="deliverOrder">

                <a href="{{ route('send') }}">
                    <button class="btn mx-auto" type="button">
                        ثبت سفارش
                    </button>
                </a>

            </div>
        @else
            <div id="empty_card" class="dFlex emptyListOrder">
                <div>
                    فعلا سفارشی ثبت نشده
                </div>
                <div>
                    ما منتظر ثبت سفارش شما هستیم
                </div>
                <img src="{{asset('assets/photo/empty list order.svg')}}" alt="">
            </div>
        @endif
    </div>

    <script>
        const p2e = s => s.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))

        function add_reserv() {
                {{--const price = {{ setting('site.reserv_cafe') }};--}}

            const number_of_reserv = parseInt(p2e($("#count_of_reserv").text())) + 1;
            $("#count_of_reserv").text(e2p(number_of_reserv + ""));
            $("#number_of_re").val(number_of_reserv);
            // $("#reserv_price").text((price * number_of_reserv) + " تومان")
        }

        function delete_reserv() {
                {{--const price = {{ setting('site.reserv_cafe') }};--}}

            const number_of_reserv = parseInt(p2e($("#count_of_reserv").text())) - 1;
            if (number_of_reserv < 1)
                return;
            $("#count_of_reserv").text(e2p(number_of_reserv + ""));
            $("#number_of_re").val(number_of_reserv);
            // $("#reserv_price").text((price * number_of_reserv) + " تومان")
        }

        function add_reserv1() {
                {{--const price = {{ setting('site.reserv_cafe') }};--}}

            const number_of_reserv = parseInt(p2e($("#count_of_reserv_1").text())) + 1;
            $("#count_of_reserv_1").text(e2p(number_of_reserv + ""));
            $("#number_of_re").val(number_of_reserv);
            // $("#reserv_price").text((price * number_of_reserv) + " تومان")
        }

        function delete_reserv1() {
                {{--const price = {{ setting('site.reserv_cafe') }};--}}

            const number_of_reserv = parseInt(p2e($("#count_of_reserv_1").text())) - 1;
            if (number_of_reserv < 1){
                rempove_reserve_cafe();
                return;
            }
            $("#count_of_reserv_1").text(e2p(number_of_reserv + ""));
            $("#number_of_re").val(number_of_reserv);
            // $("#reserv_price").text((price * number_of_reserv) + " تومان")
        }

        function show_reserve_cafe() {
            $("#cart_card").hide();
            $("#cart_card_1").hide();
            $("#empty_card").hide();
            $("#reserve_card").hide();

            $("#reserve_cafe_card").show();

        }

        function rempove_reserve_cafe() {
            $("#cart_card").show();
            $("#cart_card_1").show();
            $("#empty_card").show();
            $("#reserve_card").show();

            $("#reserve_cafe_card").hide();

        }

    </script>
@endsection

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>

@endsection
