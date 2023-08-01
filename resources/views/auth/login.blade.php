@extends('auth.index')
@section('content')
    <div class="container-fluid">
        <div class="imageMobile d-block d-md-none">
            <img src="{{asset('assets/photo/login mobile.svg')}}" alt=""/>
        </div>

        <div class="row">
            <div class="col-12 col-md-7">
                <div class="login">
                    <h2 class="d-none d-md-block">ورود به حساب کاربری</h2>

                    <form method="post" action="{{ route('login') }}" >
                        @csrf
                        <div class="">
                            <label for="number">شماره موبایل خود را وارد کنید</label>
                            <input
                                type="number"
                                class="mx-3"
                                name="phone"
                                placeholder="شماره موبایل"
                            />
                        </div>
                        @error('phone')
                        <br>
                        <label style="color:red;">
                            {{ $message }}
                        </label>
                        @enderror

                        <button type="submit" class="btn">ادامه</button>
                    </form>
                </div>
            </div>

            <div class="col-5">
                <div class="image d-none d-md-block">
                    <img src="{{asset('assets/photo/login.svg')}}" alt=""/>
                </div>
            </div>
        </div>

    </div>
@endsection
