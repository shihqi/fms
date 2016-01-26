<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Feed;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use File;
use XmlParser;
use Validator;
use DB;
use Input;
use Lexer;
use Interpreter;
use LexerConfig;

class FeedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $feed = Feed::where('id',$id);
        $products = Product::where('feed_id',$id)->get();
        
        if (Input::has('edit')){
            switch($request->input('edit')){
                case 'filter':
                    $enables = Product::where('feed_id',$id)->get();
                    $disables = Product::onlyTrashed()->where('feed_id',$id)->get();
                    return view('feedsproduct.filter',compact('enables','disables','feed'));
                    break;
                default:
                    $products = Product::where('feed_id',$id)->get();
                    return view('feedsproduct.index',compact('products','feed'));
                    break;
            }
        }
        
        $feed = Feed::where('id',$id);
        $products = Product::where('feed_id',$id)->get();
        //echo "count:".count($products)."<br/>";
        //foreach ($products as $product) {
            //echo $product->name;
        //}
        //var_dump($products);
        return view('feedsproduct.index',compact('products','feed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $products = Product::where('feed_id',$id);
        return view('feedsproduct.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $feedid = $request->input('feed_id');
        if (Input::get('update') === '1') {    // 只更新
            if ($request->hasFile('feed')) {
                //
                $file = $request->file('feed');
                $extension = $file->getClientOriginalExtension();
                Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
                switch($extension){
                    //case "csv":
                    case "tsv":
                            $products = array();
                            $config = new LexerConfig();
                            $config->setDelimiter("\t");
                            $lexer = new Lexer($config);
                            $interpreter = new Interpreter();
                            $interpreter->addObserver(function(array $row) use (&$products) {
                                $products[] = array(
                                    'id' => $row[0],
                                    'name' => $row[1],
                                    'description' => $row[2],
                                    'url' => $row[3],
                                    'image' => $row[4],
                                    'category' => $row[5],
                                    'brand' => $row[6],
                                    'google_category' => $row[7],
                                    'condition' => $row[8],
                                    'availability' => $row[9],
                                    'price' => trim($row[10],' TWD'),
                                    'retail_price' => trim($row[11],' TWD'),
                                );
                            });
                            $lexer->parse($file, $interpreter);
                            //print_r($products);
                            DB::beginTransaction();
                            try{
                                unset($products[0]); // 去掉第一行 Title
                                foreach ($products as $item){
                                    //  已存在
                                    //echo strlen(trim($item['id']))."-".$item['id']."-".(bool)($item['id'] == 'ID')."<br/>";
                                    $item['feed_id'] = $feedid;
                                    if(Product::where('feed_id', $feedid)->where('id', $item['id'])->exists()){
                                        //  update
                                        $affectedRows = Product::where('feed_id', $feedid)->where('id', $item['id'])->update($item);
                                    }
                                    else{
                                        if(Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item['id'])->exists()){
                                            Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item['id'])->restore();
                                            //  update
                                            $affectedRows = Product::where('feed_id', $feedid)->where('id', $item['id'])->update($item);
                                        }else{
                                            //  insert
                                            $insert = Product::create($item);
                                        }
                                    }
                                }
                                DB::commit();
                                return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'File Store !: 新增產品共[ '.count($products).' ]項');
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                                switch($e->getCode()){
                                    case 23000:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$feedid.' ]已有產品ID[ '.$item['id'].' ]');
                                        break;
                                    default:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error!:'.$e->getMessage().'Line:'.$e->getLine());  
                                }
                            }
                            break;
                    case "xml":
                            $xml = simplexml_load_file($file);
                            $items = $xml->channel->item;
                            DB::beginTransaction();
                            try{
                                foreach ($items as $item){
                                    //  已存在
                                    if(Product::where('feed_id', $feedid)->where('id', $item->product_id)->exists()){
                                        //  update
                                        $affectedRows = Product::where('feed_id', $feedid)->where('id', $item->product_id)->update(array('feed_id'=>$feedid,
                                                                                                                                        'id'=>$item->product_id,
                                                                                                                                        'name'=>$item->product_name,
                                                                                                                                        'description'=>$item->product_description,
                                                                                                                                        'url'=>$item->product_url,
                                                                                                                                        'image'=>$item->product_image,
                                                                                                                                        'price'=>$item->product_price,
                                                                                                                                        'retail_price'=>$item->product_retail_price,
                                                                                                                                        'category'=>$item->product_category,
                                                                                                                                        'google_category'=>$item->product_google_category,
                                                                                                                                        'brand'=>$item->product_name,
                                                                                                                                        'condition'=>$item->product_condition,
                                                                                                                                        'availability'=>$item->product_availability,
                                                                                                                                    ));
                                    }
                                    else{
                                        //  insert
                                        $insert = Product::create(array('feed_id'=>$feedid,
                                                                    'id'=>$item->product_id,
                                                                    'name'=>$item->product_name,
                                                                    'description'=>$item->product_description,
                                                                    'url'=>$item->product_url,
                                                                    'image'=>$item->product_image,
                                                                    'price'=>$item->product_price,
                                                                    'retail_price'=>$item->product_retail_price,
                                                                    'category'=>$item->product_category,
                                                                    'google_category'=>$item->product_google_category,
                                                                    'brand'=>$item->product_name,
                                                                    'condition'=>$item->product_condition,
                                                                    'availability'=>$item->product_availability,
                                                                        ));

                                    }
                                }
                                DB::commit();
                                return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'File Store !: 新增產品共[ '.count($items).' ]項');
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                                switch($e->getCode()){
                                    case 23000:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$feedid.' ]已有產品ID[ '.$item->product_id.' ]');
                                        break;
                                    default:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error!:'.$e->getMessage().'Line:'.$e->getLine());  
                                }
                            }
                            break;
                    }
            }
        }
        else{
            if ($request->hasFile('feed')) {
                //
                $file = $request->file('feed');
                $extension = $file->getClientOriginalExtension();
                Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
                
                switch($extension){
                    //case "csv":
                    case "tsv":
                            $products = array();
                            $config = new LexerConfig();
                            $config->setDelimiter("\t");
                            $lexer = new Lexer($config);
                            $interpreter = new Interpreter();
                            $interpreter->addObserver(function(array $row) use (&$products) {
                                $products[] = array(
                                    'id' => $row[0],
                                    'name' => $row[1],
                                    'description' => $row[2],
                                    'url' => $row[3],
                                    'image' => $row[4],
                                    'category' => $row[5],
                                    'brand' => $row[6],
                                    'google_category' => $row[7],
                                    'condition' => $row[8],
                                    'availability' => $row[9],
                                    'price' => trim($row[10],' TWD'),
                                    'retail_price' => trim($row[11],' TWD'),
                                );
                            });
                            $lexer->parse($file, $interpreter);
                            //print_r($products);
                            DB::beginTransaction();
                            try{
                                unset($products[0]); // 去掉第一行 Title
                                //  將已有的資料delete
                                $affectedRows = Product::where('feed_id', $feedid)->delete();
                                // 
                                foreach ($products as $item){
                                    //  已存在
                                    $item['feed_id'] = $feedid;
                                    if(Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item['id'])->exists()){
                                        //  restore
                                        Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item['id'])->restore();
                                        //  update
                                        $affectedRows = Product::where('feed_id', $feedid)->where('id', $item['id'])->update($item);
                                    }
                                    else{
                                        //  insert
                                        $insert = Product::create($item);
                                    }
                                }
                                DB::commit();
                                return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'File Store !: 新增產品共[ '.count($products).' ]項');
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                                switch($e->getCode()){
                                    case 23000:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$feedid.' ]已有產品ID[ '.$item->product_id.' ]');
                                        break;
                                    default:
                                        return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error!:'.$e->getMessage());  
                                }
                            }
                            break;
                    case "xml":
                        $xml = simplexml_load_file($file);
                        $items = $xml->channel->item;
                        DB::beginTransaction();
                        try{
                            //  將已有的資料delete
                            $affectedRows = Product::where('feed_id', $feedid)->delete();
                            //  
                            foreach ($items as $item){
                                //  已存在
                                if(Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item->product_id)->exists()){
                                    //  restore
                                    Product::onlyTrashed()->where('feed_id', $feedid)->where('id', $item->product_id)->restore();
                                    //  update
                                    $affectedRows = Product::where('feed_id', $feedid)->where('id', $item->product_id)->update(array('feed_id'=>$feedid,
                                                                                                                                    'id'=>$item->product_id,
                                                                                                                                    'name'=>$item->product_name,
                                                                                                                                    'description'=>$item->product_description,
                                                                                                                                    'url'=>$item->product_url,
                                                                                                                                    'image'=>$item->product_image,
                                                                                                                                    'price'=>$item->product_price,
                                                                                                                                    'retail_price'=>$item->product_retail_price,
                                                                                                                                    'category'=>$item->product_category,
                                                                                                                                    'google_category'=>$item->product_google_category,
                                                                                                                                    'brand'=>$item->product_name,
                                                                                                                                    'condition'=>$item->product_condition,
                                                                                                                                    'availability'=>$item->product_availability,
                                                                                                                                ));
                                }
                                else{
                                $insert = Product::create(array('feed_id'=>$feedid,
                                                                'id'=>$item->product_id,
                                                                'name'=>$item->product_name,
                                                                'description'=>$item->product_description,
                                                                'url'=>$item->product_url,
                                                                'image'=>$item->product_image,
                                                                'price'=>$item->product_price,
                                                                'retail_price'=>$item->product_retail_price,
                                                                'category'=>$item->product_category,
                                                                'google_category'=>$item->product_google_category,
                                                                'brand'=>$item->product_name,
                                                                'condition'=>$item->product_condition,
                                                                'availability'=>$item->product_availability,
                                                                    ));
                                }
                            }
                            DB::commit();
                            return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'File Store !: 新增產品共[ '.count($items).' ]項');
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                            switch($e->getCode()){
                                case 23000:
                                    return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$feedid.' ]已有產品ID[ '.$item->product_id.' ]');
                                    break;
                                default:
                                    return redirect()->action('FeedProductController@index', [$feedid])->with('message', 'Error!:'.$e->getMessage());  
                            }
                        }
                        break;
                }
            }
        }
        /*if ($insert){
            return redirect()->action('FeedProductController@index', [$id])->with('message', 'File Store!');
        }
        else{
            return redirect()->action('FeedProductController@index',compact('feed'))->with('message', 'Error!');;
        }*/
        // return redirect('feedsproduct.index',compact('feed'))->with('message', 'File Store!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($feedid, $id)
    {
        //
        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($feedid, $id)
    {
        //
        $feed = Feed::where('id',$feedid);
        $products = Product::where('feed_id', $feedid)->where('id', $id)->get();
        //echo $feedid."<br/>";
        //echo $id."<br/>";
        //var_dump(count($products));
        return view('products.edit',compact('products','feed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $feedid, $id)
    {
        if (Input::has('action')){
            // Filter 作業
            switch($request->input('action')){
                case 'enable':
                    $enable_checked = Input::get('enable');
                    if(is_array($enable_checked))
                    {
                        //
                        $products = Product::onlyTrashed()->whereIn('id', $enable_checked)->get();
                        foreach ($products as $product){
                            $product->restore();
                        }
                        // redirect
                        return Redirect::back()->with('message','Products Activate successfully');
                        //return redirect()->action('FeedProductController@index', [$id])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$id.' ]已有產品ID[ '.$item->product_id.' ]');
                    }
                    break;
                case 'disable':
                    $disable_checked = Input::get('disable');
                    if(is_array($disable_checked))
                    {
                        //
                        $products = Product::whereIn('id', $disable_checked)->get();
                        foreach ($products as $product){
                            $product->delete();
                        }
                        // redirect
                        //Session::flash('message', 'Successfully deleted the platform!');
                        return Redirect::back()->with('message','Product Disable successfully');
                        //return redirect()->action('FeedProductController@index', [$id])->with('message', 'Error !: KEY 值重複 Feed_id:[ '.$id.' ]已有產品ID[ '.$item->product_id.' ]');
                    }
                    break;
                
            }
 
        }
        else{
            // 單一產品資料更新
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description'=> 'required|max:3000',
                'url'=> 'required|max:300|url',
                'image'=> 'required|max:300|url',
                'price'=> 'required|integer',
                'retail_price'=> 'required|integer',
                'category'=> 'required|max:100',
                'google_category'=> 'required|max:100',
                'brand'=> 'required|max:20',
                'condition'=> "required|in:new,used,refurbished",
                'availability'=> 'required|in:in stock,out of stock,preorder',
            ]);

            if ($validator->fails()) {
                //return redirect('admin/feeds/'.$feedid.'/product/'.$id)
                return Redirect::route('admin.feeds.product.edit', array($feedid,$id))
                    ->withInput()
                    ->withErrors($validator);
            }

            try{
            $product = Product::where('feed_id', $feedid)->where('id', $id)->first();
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->url = $request->input('url');
            $product->image = $request->input('image');
            $product->price = $request->input('price');
            $product->retail_price = $request->input('retail_price');
            $product->category = $request->input('category');
            $product->google_category = $request->input('google_category');
            $product->brand = $request->input('brand');
            $product->condition = $request->input('condition');
            $product->availability = $request->input('availability');
            $product->save();
            }
            catch(Exception $e){

            }
            return Redirect::route('admin.feeds.product.index', array($feedid))->with('message','Save - 產品內容更新完成!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        
    }
}
