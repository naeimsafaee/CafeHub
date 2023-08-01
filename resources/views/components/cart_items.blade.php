<div class="row cardOrderIn">
    <img src="{{ Voyager::image($cart->item->image) }}"  class="cardOrderImg" alt="" style="width:100px">
    <div class="my-auto">
        <h6>{{ $cart->item->title }}</h6>
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
    <button class="delete btn" id="Decrease" type="button">
        <img src="{{asset('assets/photo/delete.svg')}}" alt="">
    </button>
    <button class="plus btn" id="Increase" type="button">
        <img src="{{asset('assets/photo/plus.svg')}}" alt="">
    </button>
</div>
</div>
