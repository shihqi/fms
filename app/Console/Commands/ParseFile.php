<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Feed;
use App\Models\Product;
use DB;

class ParseFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the Feed files needs to process';

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
        $storage = Storage::disk('ftp');
        $files = Storage::disk('ftp')->files('/public_html/feed/complete');
        //$directories = Storage::disk('ftp')->directories('public_html');
        if (count($files)>0){
            //echo explode("_",basename($files[0]))[0];
            $contents = Storage::disk('ftp')->get($files[0]);
            // ip xml 
            $dom = simplexml_load_string($contents) or die('feed file not loading');
            //echo count($dom->channel->item);
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
            $feed = Feed::where('location',explode("_",basename($files[0]))[0])->firstOrFail();
            //echo explode("_",basename($files[0]))[0];
            //echo $feed->id;
            //var_dump($feed);
            $feedId = $feed->id;
            DB::beginTransaction();
            try{
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
            
            // 複製到完成資料夾
            Storage::disk('ftp')->copy($files[0],str_replace('complete','done/complete/'.date("Ymd"),$files[0]));
            //刪除原檔案
            Storage::disk('ftp')->delete($files[0]);            
        }
        // check file in modify folder 
        else{
            $files = Storage::disk('ftp')->files('/public_html/feed/modify');
            //$directories = Storage::disk('ftp')->directories('public_html');
            if (count($files)>0){
                //echo explode("_",basename($files[0]))[0];
                $contents = Storage::disk('ftp')->get($files[0]);
                // ip xml 
                $dom = simplexml_load_string($contents) or die('feed file not loading');
                //echo count($dom->channel->item);
                $feed = Feed::where('location',explode("_",basename($files[0]))[0])->firstOrFail();
                $feedId = $feed->id;
                DB::beginTransaction();
                try{
                    //
                    for($i=0;$i<count($dom->channel->item);++$i){
                        // action
                        $action = $dom->channel->item[$i]->attributes()->action;
                        // product data
                        $products[] = array(
                                        'feed_id' => $feedId,
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
                        // process
                        switch($action){
                            case "add":
                                if(Product::withTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                                    if(Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                                        //  restore
                                        Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->restore();
                                        //  update
                                        $affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                                        $this->info('Product restore and update:'. $item['id']);
                                    }
                                    else{
                                        // add 的產品已存在
                                        $this->error('Product to add existed! '. $item['id']);
                                    }
                                }
                                else{
                                    //  insert
                                    $insert = Product::create($item);
                                    $this->info('Product insert:'. $item['id']);
                                }
                                break;
                            case "delete":
                                if(Product::where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                                    //  delete
                                    Product::where('feed_id', $feedId)->where('id', $item['id'])->delete();
                                    $this->info('Product delete:'. $item['id']);
                                }
                                break;
                            case "modify":
                                if(Product::withTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                                    if(Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                                        //  restore
                                        Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->restore();
                                        //  update
                                        $affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                                        $this->info('Product restore and update by modify :'. $item['id']);
                                    }
                                    else{
                                       //  update
                                        $affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                                        $this->info('Product restore and update by modify :'. $item['id']);
                                    }
                                }
                                break;

                        }
                        
                    }
                    // 
                    //foreach ($products as $item){
                        //  已存在
                        //$item['feed_id'] = $feedId;
                        //if(Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->exists()){
                            //  restore
                            //Product::onlyTrashed()->where('feed_id', $feedId)->where('id', $item['id'])->restore();
                            //  update
                            //$affectedRows = Product::where('feed_id', $feedId)->where('id', $item['id'])->update($item);
                            //$this->info('DB update:'. $item['id']);
                        //}
                        //else{
                            //  insert
                            //$insert = Product::create($item);
                            //$this->info('DB insert:'. $item['id']);
                        //}
                    //}
                    DB::commit();
                    // log
                    $this->info(date('Y-m-d h:i:s').' Product commit:'. $feedId);
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

                // 複製到完成資料夾
                Storage::disk('ftp')->copy($files[0],str_replace('modify','done/modify/'.date("Ymd"),$files[0]));
                //刪除原檔案
                Storage::disk('ftp')->delete($files[0]);            
            }
            
        }
    }
}
