<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Feed;
use Input;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    
    public function appier(Request $request, $id)
    {
        
        // Generate Appier FeedContent
        $feed = Feed::where('id',$id)->get();
        $total = Product::where('feed_id', $id)->count();
        if ($feed->isEmpty()){
            return view(404);
        }
        if (Input::has('start')){
            if (Input::has('limit')){
                $products = Product::where('feed_id', $id)->skip($request->input('start'))->take($request->input('limit'))->get();
            }
            else{
                // 預設兩萬筆
                $products = Product::where('feed_id', $id)->skip($request->input('start'))->take(5000)->get();
            }
        }
        else{
            if (Input::has('limit')){
                $products = Product::where('feed_id', $id)->take($request->input('limit'))->get();
            }
            else{
                // 預設兩萬筆
                $products = Product::where('feed_id', $id)->take(5000)->get();
            }
        }
        //$products = Product::where('feed_id', $id)->get();
        
        if ($request->input('action')=='1'){
        return response()->view('media.appier',compact('products','feed','total'))->header('Content-Description', 'File Transfer')->header('Content-Disposition', 'attachment; filename='.$feed->first()->description.'.xml')->header('Content-Transfer-Encoding', 'binary');
        }
        else {
            return view('media.appier',compact('products','feed','total'));
        }
        /*$rss = new SimpleXMLExtended('<rss xmlns:g="http://base.google.com/ns/1.0"/>');
        $NS = array( 
           'g' => 'http://base.google.com/ns/1.0' 
        ); 
        $rss->registerXPathNamespace('g', $NS['g']); 

        $rss->addAttribute('version', '2.0');
        $rss->addChild('channel');
        $xml1 = $rss->channel;
        $xml1->addChild('title', "gohappy datafeed");
        $xml1->addChild('link', "gohappy datafeed");
        $xml1->addChild('description', "gohappy datafeed");
        $xml1->addChild('date',$angle);
        $ary_exist = [];
        $rss->addChild('Updated', date('Y-m-d H:i:s'));

        foreach ($products as $product){
                $item = $xml1->addChild('item');
                $id = $item->addChild("id",'',$NS['g']);
                $id->addCData($product->id);

                $title = $item->addChild("title",'',$NS['g']);
                $title->addCData($product->name);
                $description = $item->addChild("description",'',$NS['g']);
                $description->addCData($product->description);
                $item->addChild('image_link', $product->image, $NS['g']);
                $link = $item->addChild('link');
                $item->link = $product->url;
                $item->addChild('price', $product->retail_price, $NS['g']);
                $item->addChild('sale_price', $product->price, $NS['g']);
                $item->addChild('brand', $product->brand, $NS['g']);
                $item->addChild('google_product_category', $product->google_category, $NS['g']);
                $item->addChild('product_type', $product->google_category, $NS['g']);
                $item->addChild('availability', $product->availability, $NS['g']);
                $item->addChild('condition', $product->condition, $NS['g']);
        }
        foreach ($NS as $prefix => $name) { 
            $rss->registerXPathNamespace($prefix, $name); 
        }
        return Response::make($rss, '200')->header('Content-Type', 'text/xml');
        */
        /*
        http://blog.kongnir.com/2014/06/27/getting-laravel-to-return-a-view-as-rss-or-xml/
        $content = View::make('home')->with('somevar', $somevar);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
        */
    }
    
     public function google(Request $request,$id)
    {
         // Generate Google FeedContent
        $feed = Feed::where('id',$id)->get();
        $total = Product::where('feed_id', $id)->count();
         
        if ($feed->isEmpty()){
            return view(404);
        }
        if (Input::has('start')){
            if (Input::has('limit')){
                $products = Product::where('feed_id', $id)->skip($request->input('start'))->take($request->input('limit'))->get();
            }
            else{
                // 預設兩萬筆
                $products = Product::where('feed_id', $id)->skip($request->input('start'))->take(5000)->get();
            }
        }
        else{
            if (Input::has('limit')){
                $products = Product::where('feed_id', $id)->take($request->input('limit'))->get();
            }
            else{
                // 預設兩萬筆
                $products = Product::where('feed_id', $id)->take(5000)->get();
            }
        }
        //$products = Product::where('feed_id', $id)->get();
       
        if ($request->input('action')=='1'){
        return response()->view('media.google',compact('products','feed','total'))->header('Content-Description', 'File Transfer')->header('Content-Disposition', 'attachment; filename='.$feed->first()->description.'.xml')->header('Content-Transfer-Encoding', 'binary');
        }
        else {
            $outofstock = Product::onlyTrashed()->where('feed_id', $id)->get();
            return view('media.google',compact('products','feed','total','outofstock'));
        }
     }
    

}
/*
// http://coffeerings.posterous.com/php-simplexml-and-cdata
class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
  } 
}*/
