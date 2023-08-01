@if(!$item->is_available)
    @php return false; @endphp
@endif

<div class="cardMenu col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
    <img src="{{ Voyager::image($item->image) }}" class="cardMenuImg" alt=""/>
    <div class="p-3">
        <h6>{{ $item->name }}</h6>
        <div class="d-flex justify-content-between detail">
            <div class="d-flex align-items-center">
                <img src="{{asset('assets/photo/ok.svg')}}" alt=""/>
                {{ fa_number($item->rate) }}%

            </div>
            <div>{{ fa_number(number_format($item->price)) }} تومان</div>
        </div>
        @if($item->is_breakfast)
            <a>
                <button class="btn mr-auto" type="button" style="color: #fff !important;">
                    صبحانه
                </button>
            </a>
        @else
            <a href="{{ route('add_to_cart' , $item->id) }}">
                <button class="btn mr-auto" type="button">
                    <img src="{{asset('assets/photo/plus.svg')}}" alt=""/>
                </button>
            </a>
        @endif
    </div>

</div>
