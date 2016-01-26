<?php

namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParseFeed extends Command implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
