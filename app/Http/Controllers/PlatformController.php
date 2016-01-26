<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Platform;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Activity;
use Session;
class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $platforms = Platform::paginate(15);
        return view('platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('platforms.create');
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
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('admin/platforms')
                ->withInput()
                ->withErrors($validator);
        }

        $platform = new Platform;
        $platform->name = $request->name;
        $platform->save();
        //Activity::log(Auth::user()->name.' Add platform:'.$request->name);
        return redirect('admin/platforms');
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
        $platform = Platform::findOrFail($id);
        return view('platforms.edit',compact('platform'));
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
        $platform = Platform::findOrFail($id);
        $platform->name = $request->input('name');
        $platform->save();
        //$platform->update($request->all());
        return redirect('admin/platforms');
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
        $platform = Platform::findOrFail($id);
        //Activity::log(Auth::user()->name.' deleted platform:'.$platform->name);
        try{
            Activity::log(Auth::user()->name.' deleted platform:'.$platform->name);
        } catch (\Exception $e) {
            echo $e->getCode().$e->getMessage();;
        }
            
        $platform->delete();
        // redirect
        Session::flash('message', 'Successfully deleted the platform!');
        return redirect('admin/platforms');
    }
}
