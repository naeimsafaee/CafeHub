<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddMoney extends Command{

    protected $signature = 'wallet:add';

    protected $description = "add money to wallets";

    public function __construct(){
        parent::__construct();

    }

    public function handle(){

        $clients = DB::table('clients')->where('is_karo', true)->update([
            "wallet" => 50000,
        ]);
        return 0;
    }
}
