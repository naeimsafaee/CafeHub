<div class="row mafia mx-auto">
    <div class="col-lg-8 col-xl-7">
        <h2 class="text-right">{{ $event->title }}</h2>
        <div class="details">
            <div class="subDeiails">
                <img src="{{asset('assets/photo/calendar.svg')}}" alt=""/>
                <span>{{ $event->date }}</span>
            </div>

            <div class="subDeiails">
                <img src="{{asset('assets/photo/clock.svg')}}" alt=""/>
                ساعت :
                <span>{{ $event->hour }}</span>
            </div>
        </div>
        <p class="d-none d-md-block">
            {!! $event->description !!}
        </p>
        <a href="{{ route('add_to_event' , $event->id) }}">
            <button class="btn" type="button">ثبت نام</button>

        </a>
    </div>
    <div class="d-none d-lg-flex justify-content-center col-lg-4 col-xl-5">
        <div class="mafiaImg">
            @if($event->image)
                <img src="{{ Voyager::image($event->image) }}" class="" alt=""/>
            @else
                <img src="{{ asset('assets/photo/mafia.svg') }}" class="" alt=""/>
            @endif
        </div>
    </div>
</div>
