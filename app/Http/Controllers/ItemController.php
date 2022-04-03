<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::simplePaginate(1);
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item, int $id)
    {
        return $item->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item, int $id )
    {
        //
        //return "test" . $request->item()->id;
        //return "test" . var_dump( $id );

        //todo share can update
        if($request->user()->id !== $id){
            return abort(403,'You tried to update others item.');
        }
        //return abort(400,'Not updated');
        $status = $item->findOrFail($id)->update($request->all());

        if($status){
            return $item->findOrFail($id);
        }

        return abort(304,'Not updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item, int $id )
    {
        //return $id;
        //return $request->user()->id;
        if($request->user()->id !== $id){
           return abort(403,'You tried to delete others item.');
        }

        $status = $user->findOrFail($id)->delete();

        if($status){
            return reponse("Removed",204);
        }

        return abort(400,"Not deleted");
    }
}
