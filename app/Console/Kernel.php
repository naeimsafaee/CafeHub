<?php

namespace App\Console;

use App\Models\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel{

    protected $commands = [
        '\App\Console\Commands\AddMoney',
    ];

    protected function schedule(Schedule $schedule){
        $schedule->command('wallet:add')->daily()->withoutOverlapping();
    }

    protected function commands(){
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
