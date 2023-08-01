@extends('auth.index')

@section('content')

    <div class="container-fluid">
        <div class="row">

            <div class="col-12 justify-content-center">
                <div class="successful dFlex mx-auto">
                    <img
                        src="{{asset('assets/photo/successful.svg')}}"
                        class="imgSuccess"
                        alt=""/>

                    <h4 class="w-100">ثبت شد با شما تماس میگیریم به زودی</h4>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('css')

    <link rel="stylesheet" href="{{asset('assets/css/payment.css')}}"/>

@endsection
