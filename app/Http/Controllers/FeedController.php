<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feed;
use App\Models\Customer;
use App\Models\Role;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use Activity;
use Session;
use File;
use Entrust;


class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //
        //echo Auth::user()->hasRole('Administrator');
        //echo $userID = Auth::id();
        //echo Entrust::hasRole('Administrator');
        //echo Entrust::hasRole('Administrator');
        $feeds = Feed::paginate(15);
        return view('feeds.index', compact('feeds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $customers = Customer::lists('name', 'id');
        //$customers = Customer::where('id', $feed->first()->customer_id)->lists('name','id');
        return view('feeds.create',compact('customers'));
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
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric',
            'location' => 'required|max:100', //|url
            'name' => 'required|max:100',
            'description' => 'required|max:150',
            'content' => 'required|in:complete,modify',
            'type' => 'required|in:file,url',
        ]);
        
        if ($validator->fails()) {
            return redirect('admin/feeds/create')
                ->withInput()
                ->withErrors($validator);
        }
        if ($request->hasFile('feed')) {
            //
            $file = $request->file('feed');
            $extension = $file->getClientOriginalExtension();
            Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
            
            
            //return Redirect::to('admin/users/create')
            //->withInput()
            //->withErrors(array('message' => 'Login field is required.'));
            //http://stackoverflow.com/questions/18367769/how-to-use-witherrors-with-exception-error-messages-in-laravel4
        }
        //$input = $request->all();
        //Feed::create($input);
        $feed = new Feed;
        $feed->customer_id = $request->customer_id;
        $feed->name = $request->name;
        $feed->location = $request->location;
        $feed->type = $request->type;
        $feed->content = $request->content;
        $feed->description = $request->description;
        $feed->save();
        //$feed = new Feed(Input::all())->save();

        Activity::log(Auth::user()->name.' Add feed:'.$request->name);
        //Activity::log(Auth::user()->name.' Add file:'.$file->name);
        return redirect('admin/feeds');
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
        $feed = Feed::where('id',$id)->get();
        //$customers = Customer::where('id', $feed->first()->customer_id)->lists('name','id');
        $customers = Customer::where('id', $feed->first()->customer_id)->get();
        return view('feeds.edit',compact('customers','feed'));
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
         //
        $feed = Feed::findOrFail($id);
        $feed->name = $request->input('name');
        $feed->description = $request->input('description');
        $feed->location = $request->input('location');
        $feed->type = $request->input('type');
        $feed->content = $request->input('content');
        $feed->save();
        //$platform->update($request->all());
        return redirect('admin/feeds');
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
