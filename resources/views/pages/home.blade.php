@extends('master')

@section('master-content')

    <div class="middle">
        <div class="header d-flex justify-content-center align-items-center">
            <div class="right ml-auto">
                <div class="item">
                    <a href="{{ route('last_cart') }}">
                        <img src="{{asset('assets/photo/Past orders.svg')}}" alt=""/>
                        سفارش های گذشته
                    </a>
                </div>
                <div class="item d-none d-md-inline">
                    <a data-toggle="modal" data-target="#mapModal"> مسیریابی کافه </a>
                </div>
                <div class="item d-none d-md-inline">
                    <a onclick="show_reserve_cafe()"> رزرو کافه </a>
                </div>
                <div class="item d-none d-md-inline">
                    <a href="{{ route('contact_us') }}">تماس با ما</a>
                </div>
            </div>

            <div class="left d-flex align-items-center">
                <a data-toggle="modal" data-target="#downloadModal" class="dwnApp d-none d-md-inline-flex">
                    دانلود اپلیکیشن
                </a>
                <img src="{{ Voyager::image(setting("site.logo")) }}" class="d-inline" alt=""/>
            </div>
        </div>

        <div class="mobileHead d-md-none">
            <div class="item">
                <a data-toggle="modal" data-target="#downloadModal" class="dwnApp">
                    دانلود اپلیکیشن
                </a>
            </div>
            <div class="item"><a data-toggle="modal" data-target="#mapModal"> مسیریابی کافه </a></div>
            <div class="item">
                <a data-toggle="modal" data-target="#reserveModal" onclick="show_reserv()"> رزرو کافه </a>
            </div>
            <div class="item">
                <a href="{{ route('contact_us') }}">تماس با ما</a>
            </div>
            {{--            <div class="item"><a data-toggle="modal" data-target="#shoppingBagO" onclick="show_cart()"> سبد خرید </a></div>--}}
        </div>
        @each('components.events' , $events , 'event')

        @if($always && count($always) > 0)
            <div class="always d-flex align-items-center page-section">
                <div class="alwaysImg d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/photo/always.svg') }}" class="alwaysImg" alt=""/>
                </div>
                <h6 class="d-inline">همون همیشگی</h6>
            </div>


            <div class="menu row justify-content-center justify-content-md-start mx-auto" style="width: 95%;">
                @each('components.products' , $always , 'item')
            </div>
        @endif

        @php
            $__categories = \App\Models\Category::query()->orderBy('sort')->get();
        @endphp

        @foreach($__categories as $category_item)

            @php
                $p_items = $category_item->items;
            @endphp

            @php
                $category = $category_item;
            @endphp
            <div class="always d-flex align-items-center" id="{{ $loop->index }}">
                <div class="alwaysImg d-flex justify-content-center align-items-center">
                    <img src="{{ getFile($category->image) }}" class="alwaysImg" alt=""/>
                </div>

                <h6 class="d-inline">{{ $category->title }} </h6>
            </div>

            <div class="menu row justify-content-center justify-content-md-start mx-auto w95">
                @each('components.products' , $p_items , 'item')
            </div>

        @endforeach

        @include('footer')

    </div>
    {{--map modal--}}
    <div class="modal  fade pr-0" id="mapModal">
        <div class="modal-dialog dialog-centered">
            <div class="modal-content mx-auto">
                <div class="modal-body dFlex">
                    <button class="close btn" type="button" data-dismiss="modal">
                        <img src="{{asset('assets/photo/close.svg')}}" alt="">
                    </button>

                    <h2>مسیریابی کافه</h2>

                    <div class="text">آدرس کافه: تهران - تهران - مازندران - کافه هاب</div>

                    <div class="btns">
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ setting('home.location') }}&travelmode=driving"
                           target="_blank">
                            <button class="btn modalBtn" type="button">مسیریابی با گوگل مپ</button>
                        </a>

                        <br>
                        <a href="https://www.waze.com/live-map/directions?navigate=yes&to=ll.{{ setting('home.location') }}"
                           target="_blank">
                            <button class="btn modalBtn" type="button">مسیریابی با ویز</button>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--reserve modal--}}
    <div class="modal  fade pr-0" id="reserveModal">
        <div class="modal-dialog dialog-centered">
            <div class="modal-content mx-auto">
                <div class="modal-body dFlex">
                    <button class="close btn" type="button" data-dismiss="modal">
                        <img src="{{asset('assets/photo/close.svg')}}" alt="">
                    </button>

                    <div class="cards">

                        <div class="cardOrder row">

                            <div class="reserve mx-auto">
                                <h2>رزور کافه</h2>

                                <div class="reserveIcon mx-auto">
                                    <img src="{{asset('assets/photo/reserve icon.svg')}}" alt="">
                                </div>

                                <div class="number mx-auto dFlex">
                                    <span id="count_of_reserv" name="count">1</span>
                                    <span>نفر</span>
                                    <button class="delete btn" type="button" onclick="delete_reserv()">
                                        <img src="{{asset('assets/photo/delete.svg')}}" alt="">
                                    </button>
                                    <button class="plus btn" type="button" onclick="add_reserv()">
                                        <img src="{{asset('assets/photo/plus.svg')}}" alt="">
                                    </button>
                                </div>

                                <div class="mx-auto" style="width: 306px;margin-top: 28px">

                                    <form method="post" id="main_form" action="{{ route('reserve_cafe') }}">
                                        @csrf
                                        <input type='hidden' name='recaptcha_token' id='recaptcha_token'>
                                        @if($errors->has('recaptcha_token'))
                                            {{$errors->first('recaptcha_token')}}
                                        @endif
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

                        </div>


                        <hr class="mx-auto">

                        {{-- <div class="df dFlex">
                             <div>جمع سفارش</div>
                             <div id="reserv_price" class="moneyPlus">{{ setting('site.reserv_cafe') }} تومان</div>
                         </div>--}}

                        <button onclick="document.getElementById('main_form').submit()" type="button"
                                class="btn mafiaBtn mx-auto mb-5">تایید
                        </button>
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{--download app--}}
    <div class="modal  fade pr-0" id="downloadModal">
        <div class="modal-dialog dialog-centered">
            <div class="modal-content mx-auto">
                <div class="modal-body dFlex">
                    <button class="close btn" type="button" data-dismiss="modal">
                        <img src="{{asset('assets/photo/close.svg')}}" alt="">
                    </button>

                    <h2>دانلود اپلیکیشن اندروید</h2>


                    <div class="btns">
                        <a href="{{ setting('home.bazzar') }}">
                            <button class="btn modalBtn" type="button">دانلود از کافه بازار</button>

                        </a>
                        <br>
                        <a href="{{ setting('home.g_play') }}">
                            <button class="btn modalBtn" type="button">دانلود از گوگل پلی</button>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    event modal--}}
    @if($has_reserve)
        <div class="modal show fade pr-0" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModal"
             aria-hidden="true">
            <div class="modal-dialog dialog-centered">
                <div class="modal-content h-auto mx-auto">
                    <div class="modal-body dFlex">
                        <button class="close btn" type="button" data-dismiss="modal">
                            <img src="{{ asset('assets/photo/close.svg') }}" alt="">
                        </button>

                        <div class="cards">
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
                                <div class="moneyPlus">{{ fa_number(number_format($reserve_event->sum('amount'))) }}
                                    تومان
                                </div>
                            </div>

                            <a href="{{ route('pay_reserve') }}">

                                <button type="button" class="btn mafiaBtn mx-auto mb-5">تایید و پرداخت</button>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function show_reserv() {
            if (window.innerWidth < 1200) {
                /* $('#cartModal').modal({
                     show: true
                 });*/
            }
        }
    </script>


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

        <div id="reserve_cafe_card" style="display: @if($errors->count() > 0) block @else none @endif" class="cards">

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
                            <span style="color: red;margin-bottom: 12px;display: block;">
                                    {{ $message }}
                                </span>
                            @enderror

                            <label for="name">نام خود را وارد کنید</label>
                            <input placeholder="نام شما" type="text" name="name" id=""/>
                            @error('name')
                            <span style="color: red;margin-bottom: 12px;display: block;">
                                    {{ $message }}
                                </span>
                            @enderror

                            <label for="phone">شماره موبایل خود را وارد کنید</label>
                            <input placeholder="شماره موبایل" type="number" name="phone" id=""/>
                            @error('phone')
                            <span style="color: red;margin-bottom: 12px;display: block;">
                                    {{ $message }}
                                </span>
                            @enderror

                            <input type="hidden" id="number_of_re" name="count" value="1"/>

                            <div class="number mx-auto dFlex" style="margin-bottom: 30px;margin-top: 20px">
                                <span id="count_of_reserv_1" name="count">1</span>
                                <span>نفر</span>
                                <button class="delete btn" type="button" onclick="delete_reserv1()">
                                    <img src="{{asset('assets/photo/delete.svg')}}" alt="">
                                </button>
                                <button class="plus btn" type="button" onclick="add_reserv1()">
                                    <img src="{{asset('assets/photo/plus.svg')}}" alt="">
                                </button>
                            </div>


                            {!! \Anhskohbo\NoCaptcha\Facades\NoCaptcha::display() !!}

                        </form>
                    </div>

                </div>



            </div>

            <hr class="mx-auto">

            <button onclick="document.getElementById('main_form_1').submit()" type="button"
                    class="btn mafiaBtn mx-auto mb-5">تایید و پرداخت
            </button>
        </div>

        @if($errors->count() === 0)
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
            if (number_of_reserv < 1) {
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
