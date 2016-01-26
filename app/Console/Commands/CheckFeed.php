<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feed;
use DB;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Artisan;

class CheckFeed extends Command
{
    use DispatchesCommands;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $feeds = Feed::where('type','url')->get();
        //dd($feeds);
        foreach ($feeds as $feed){
                //dd($feed->id);
                Artisan::queue('parse:feed',['id'=>$feed->id]);
        }
        
    }
}
