
<div class="row smallnav">
    <div class="col-6 d-none d-md-block">
        <a href="{{ route('home') }}">
            <img src="{{ Voyager::image(setting("site.logo")) }}" class="logoNav" alt="" />

        </a>
    </div>
    <div class="col-12 col-md-6 text-left">
        <button type="button" class="btnBackNav btn">
            <a href="{{ route('home') }}">
                <img src="{{asset('assets/photo/btn back navbar.svg')}}" alt="" />

            </a>
        </button>
    </div>
</div>
