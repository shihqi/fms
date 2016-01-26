<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feed;
use App\Models\Product;
use Parser;
use DB;

class ParseFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:feed {id : the ID of the feed}';
    //protected $signature = 'feed:parse';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse feed data from url of given feed id';

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
        $feedId = $this->argument('id');
        $feed = Feed::findOrFail($feedId);
        //$parser = new Parser();
        //$xml = $parser->xml('');
        //$item = array();
        $dom = simplexml_load_file($feed->location) or die('feed not loading');
        echo count($dom->channel->item);
        for($i=0;$i<count($dom->channel->item);++$i){
            $products[] = array(
                                //'feed_id' => $feedId,
                                'id' => $dom->channel->item[$i]->product_id,
                                'name' => $dom->channel->item[$i]->product_name,
                                'description' => $dom->channel->item[$i]->product_description,
                                'url' => urldecode($dom->channel->item[$i]->product_url),
                                'image' => urldecode($dom->channel->item[$i]->product_image),
                                'category' => $dom->channel->item[$i]->product_category,
                                'brand' => $dom->channel->item[$i]->product_brand,
                                'condition' => $dom->channel->item[$i]->product_condition,
                                'availability' => $dom->channel->item[$i]->product_availability,
                                'price' =>  trim($dom->channel->item[$i]->product_price,' TWD'),
                                'retail_price' => trim($dom->channel->item[$i]->product_retail_price,' TWD')
                            );
        }
        
        DB::beginTransaction();
        try{
            switch($feed->content){
                case "complete":
                    //  將已有的資料delete
                    $affectedRows = Product::where('feed_id', $feedId)->delete();
                    // 
                    foreach ($products as $item){
                        //  已存在
                        $item['feed_id'] = $feedId;
                        if(Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                            //  restore
                            Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->restore();
                            //  update
                            $affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                            $this->info('DB update:'. $item['id']);
                        }
                        else{
                            //  insert
                            $insert = Product::create($item);
                            $this->info('DB insert:'. $item['id']);
                        }
                    }
                    break;
                case "modify":
                    // 
                    foreach ($products as $item){
                        //  已存在
                        $item['feed_id'] = $feedId;
                        if(Product::where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                            //  update
                            $affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                            $this->info('DB update:'. $item['id']);
                        }
                        else{
                            //  insert
                            $insert = Product::create($item);
                            $this->info('DB insert:'. $item['id']);
                        }
                    }
                    break;
            }
            DB::commit();
            // log
            $this->info(date('Y-m-d h:i:s').' DB commit:'. $feedId);
        }
        catch (\Exception $e) {
            DB::rollback();
            switch($e->getCode()){
                case 23000:
                    //log
                    $this->error('DB 23000:'. $e->getLine(). $e->getCode().$e->getMessage());
                    break;
                default:
                    //log  
                    $this->error('DB error:'. $e->getLine(). $e->getCode().$e->getMessage());
            }
        }
        //var_dump($products);
        //$this->info('XXX:'. $feed->location);
    }
}
