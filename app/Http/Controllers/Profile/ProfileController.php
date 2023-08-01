<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientItemCollection;
use App\Models\Cart;
use App\Models\ClientItem;
use App\Models\Item;
use App\Models\ReservEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {

    public function __invoke(Request $request) {
        $client = auth()->guard('clients')->user();

        return view('profile.profile', compact('client'));
    }

    public function submit(Request $request) {
        Validator::make($request->all(), [
            'name' => ['required',],
            'phone' => ['required', 'size:11'],

        ], [
            'name.required' => "لطفا نام خود را وارد کنید ",
            'phone.required' => "لطفا شماره تلفن خود را وارد کنید ",
            'phone.size' => "لطفا شماره خود را به درستی وارد کنید"
        ])->validate();

        $client = auth()->guard('clients')->user();
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->save();

        return redirect()->route('profile');
    }

    public function avatar_submit(Request $request) {
        Validator::make($request->all(), [
            'avatarPicture' => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ])->validate();

        $file = $request->avatarPicture;
        $fileName = time() . '-' . rand() . '.' . $file->getClientOriginalExtension();
        Storage::put('files/' . $fileName, file_get_contents($file));
        $client = auth()->guard('clients')->user();
        $client->avatar = 'files/' . $fileName;
        $client->save();

    }

    public function last_cart() {

        $client_id = auth()->guard('clients')->user()->id;
        $carts = Cart::query()->where('client_id', $client_id)->get();
        $reserve_event = ReservEvent::query()->where('client_id', $client_id)->get();

        $client_item = ClientItem::query()->where('client_id', auth()->guard('clients')->user()->id)
            ->orderByDesc("updated_at")->with('item')->get()->groupBy('transaction_id');

        return view('profile.last_carts', compact('client_item', 'carts', 'reserve_event'));
    }

    public function order_again(Request $request, $transaction_id) {

        $items = Item::query()->whereHas('bought', function(Builder $query) use ($transaction_id) {
            $query->where('transaction_id', $transaction_id);
        })->get();

        foreach($items as $item) {
            Cart::query()->create([
                "count" => $item->bought->where('client_id', auth()->guard('clients')->user()->id)->first()->count,
                "item_id" => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ]);
        }

        return redirect()->route('home');
    }

}
