@php

    $categories = \App\Models\Category::query()->orderBy('sort')->get();

@endphp

<div class="rightBody">
    <a href="{{ route('profile') }}" class="account">
        <img src="{{asset('assets/photo/account.svg')}}" alt=""/>
    </a>

    <div class="categorymenu">
        @foreach($categories as $category)
            <a href="#{{ $loop->index }}" class="active_nav nav_sample_test" @if($loop->last) style="position: relative;display: block;" @endif>
                <img class="pNavIcon" src="{{ getFile($category->image) }}" alt="" />
                <div class="activeMenu" @if($loop->last) style="top: 0" @endif></div>
            </a>
        @endforeach
    </div>

    @if(request()->has('table'))
    <div class="call callWaiter d-lg-none mx-auto">
        <a href="{{ request()->has('table') ? route('call_waiter' , request()->table) : ''}}"
           class="linkCall d-flex justify-content-center align-items-center">
            <img src="{{asset('assets/photo/call Waiter.svg')}}" alt=""/>
        </a>
    </div>
    @endif

    <div class="call mx-auto">
        <a href="tel:{{ setting('site.phone') }}"
           class="linkCall d-flex justify-content-center align-items-center">
            <img src="{{asset('assets/photo/call.svg')}}" alt=""/>
        </a>
    </div>
</div>


