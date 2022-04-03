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
    public function index(Request $request)
    {
        $order_by = $request->get('order_by') ?: 'id';
        $order_direction = $request->get('order_direction') ?: 'asc';
        $per_page = intval($request->get('per_page') ?: 2);

        return Item::where('owner_id',$request->user()->id)->orderBy($order_by, $order_direction )->simplePaginate($per_page)->appends($request->except('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$user->roles()->attach($role->id);
        //$user->roles()->detach($role->id);
        //$user->roles()->sync($roles);

        $data = $request->all();
        //force owner_id as logged user //prevent injection
        $data['owner_id'] = $request->user()->id;

        $obj = Item::create($data);

        if($request->has('categories')){
            $status = $obj->categories()->sync( $request->get('categories') ?: [] );
        }
        //return $status;
        $obj->categories; //touch to return
        return $obj;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Item $item, int $id)
    {
        $obj = $item->findOrFail($id);

        //todo share?
        if($request->user()->id !== $obj->owner_id){
            return abort(403,'You tried to show others item.');
        }
        $obj->categories; //touch to return
        return $obj;
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

        $obj = $item->findOrFail($id);
        //todo share can update
        if($request->user()->id !== $obj->owner_id){
            return abort(403,'You tried to update others item.');
        }

        //return $request->all();
        //return abort(400,'Not updated');
        $data = $request->all();

        //return $data;

        $status = $obj->update($data);

        if($status){
            if($request->has('categories')){
                $status = $obj->categories()->sync( $request->get('categories') ?: [] );
            }
            //return $status;
            return $item->with('categories')->findOrFail($id);
        }

        return abort(304,'Not updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item, int $id )
    {
        //return $id;
        //return $request->user()->id;
        $obj = $item->findOrFail($id);

        if($request->user()->id !== $obj->owner_id){
           return abort(403,'You tried to delete others item.');
        }

        $status = $obj->delete();

        if($status){
            return response("Removed",204);
        }

        return abort(400,"Not deleted");
    }
}
