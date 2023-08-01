@extends('auth.index')

@section('content')

    <div class="container-fluid">
        <!-- nav  -->

        <div class="">
            <img src="{{ asset('assets/photo/contact-us.png') }}" class="d-none d-md-block" alt=""/>
            <img src="{{ asset('assets/photo/contact-us-mobile.png') }}" class="d-block d-md-none" alt=""/>
        </div>

        <div class="row justify-content-center contactUS mx-auto">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6 px-4">
                <h2 class="mb-4">تماس با ما</h2>
                <div class="d-flex align-items-center my-3">
                    <img src="{{ asset('assets/photo/CU Call.svg') }}" alt=""/>
                    <h6>{{ setting('site.phone') }}</h6>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/photo/CU map.svg') }}" alt=""/>
                    <h6>
                        {{ setting('site.address') }}
                    </h6>
                </div>
                <div class="mapouter">
                    <div class="gmap_canvas map-responsive">
                        <iframe
                            width="526"
                            height="351"
                            id="gmap_canvas"
                            src="{{ setting('site.location') }}"
                            frameborder="0"
                            scrolling="no"
                            marginheight="0"
                            marginwidth="0"
                        ></iframe
                        >
                        <a href="https://www.whatismyip-address.com/divi-discount/"></a
                        ><br/>
                        <a href="https://www.embedgooglemap.net"></a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9 col-lg-4 px-4">
                <div class="formCU">
                    <form action="{{ route('contact_us_submit') }}" method="POST">
                        @csrf
                        <label for="name">نام خود را وارد نمایید</label>
                        <input placeholder="نام شما" type="text" name="name" id=""/>

                        <label for="numbercall">شماره موبایل خود را وارد نمایید</label>
                        <input
                            placeholder="شماره موبایل"
                            type="number"
                            name="numbercall"
                            id=""
                        />

                        <label for="caption">توضیحات</label>
                        <textarea
                            placeholder="پیام شما"
                            name="caption"
                            id=""
                            cols="30"
                            rows="10"
                        ></textarea>

                        @if(\Session::get('message'))
                            <span style="color: green;display: block;margin-bottom: 10px;">
                                پیام شما با موفقیت ثبت شد!
                            </span>
                        @endif

                        <button type="submit" class="btn mafiaBtn mb-5">
                            ارسال پیام
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
