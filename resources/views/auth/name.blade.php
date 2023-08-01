@extends('auth.index')
@section('content')

    <div class="container-fluid">
        <div class="imageMobile d-block d-md-none">
            <img src="./assets/photo/name mobile.svg" alt=""/>
        </div>

        <div class="row">
            <div class="col-12 col-md-7">
                <div class="login">
                    <h2 class="d-none d-md-block">نام شما</h2>

                    <form method="post" action="{{ route('name') }}" class="">
                        @csrf
                        <div class="">
                            <label for="number">نام خود را وارد کنید</label>
                            <input
                                type="text"
                                class="mx-3"
                                name="name"
                                placeholder="نام شما"
                                id=""
                            />
                        </div>
                        @error('name')
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
                    <img src="./assets/photo/name.svg" alt=""/>
                </div>
            </div>
        </div>
    </div>
@endsection
