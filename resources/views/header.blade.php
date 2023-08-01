@php
    $cart_count = (\App\Models\Cart::query()->where('ip' , request()->ip())->sum('count'));
@endphp

<a class="order-list-btn" id="sefareshat" href="{{ route('cart') }}" @if($cart_count == 0) style="display: none" @endif>
    لیست سفارش (<span id="cart_count">{{ fa_number($cart_count) }}</span>)
</a>

<a href="https://play.google.com/store/apps/details?id=studio.karo.cafehub" class="download-app flex-box" id="download_box" style="height: 0; opacity: 0;">
    دریافت اپلیکیشن کافه هاب
    <img class="close" src="{{ asset('assets/icon/close.svg') }}" onclick="save_in_cookie()">
</a>
<div class="mobile-item row search-row">
    <div class="padding-item col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="flex-box search-box">
            <a href="{{ route('category') }}" class="older-list flex-box">
                <img src="{{ asset('assets/icon/older-list.svg') }}">
            </a>
            <form action="{{ route('home') }}" method="GET">
                <input name="search" type="text" placeholder=" جستجو کنید"/>
            </form>
            <img class="search-image" src="{{ asset('assets/icon/search.svg') }}">

        </div>
    </div>

</div>
