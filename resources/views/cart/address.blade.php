@extends('index')

@section('content')

    @include('small_nav')

    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="address mx-auto">
                    <h6>آدرس شما</h6>

                    <form method="post" action="{{ route('address2') }}" class="">
                        @csrf
                        <textarea placeholder="آدرس شما" class="mx-1" name="address" id="" cols="30" rows="10"></textarea>

                        <button type="submit" class="btn">تایید و پرداخت</button>
                    </form>
                </div>
            </div>


        </div>

    </div>

@endsection

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/payment.css') }}" />

@endsection
