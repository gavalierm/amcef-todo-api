<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Category
     */
    public function index(Request $request)
    {
        $order_by = $request->get('order_by') ?: 'id';
        $order_direction = $request->get('order_direction') ?: 'asc';
        $per_page = intval($request->get('per_page') ?: 2 );

        return Category::where('owner_id',$request->user()->id)->orderBy( $order_by, $order_direction )->simplePaginate($per_page)->appends($request->except('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        //force owner_id as logged user //prevent injection
        $data['owner_id'] = $request->user()->id;

        $obj = Category::create($data);

        if($request->has('items')){
            $status = $obj->items()->sync( $request->get('items') ?: [] );
        }
        //return $status;
        $obj->items; //touch to return
        return $obj;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return App\Models\Category
     */
    public function show(Request $request, Category $category, int $id)
    {
        $obj = $category->findOrFail($id);
        //todo share?
        if($request->user()->id !== $obj->owner_id){
            return abort(403,'You tried to show others item.');
        }
        return $obj;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return App\Models\Category
     */
    public function update(Request $request, Category $category, int $id )
    {

        $obj = $category->findOrFail($id);
        //todo share can update
        if($request->user()->id !== $obj->owner_id){
            return abort(403,'You tried to update others category.');
        }
        //
        $data = $request->all();

        //return $data;

        $status = $obj->update($data);

        if($status){
            if($request->has('items')){
                $status = $obj->items()->sync( $request->get('items') ?: [] );
            }
            
            return $category->with('items')->findOrFail($id);
        }

        return abort(304,'Not updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category, int $id)
    {
        //return $id;
        //return $request->user()->id;
        $obj = $category->findOrFail($id);

        if($request->user()->id !== $obj->owner_id){
           return abort(403,'You tried to delete others category.');
        }

        $status = $obj->delete();

        if($status){
            return response("Removed",204);
        }

        return abort(400,"Not deleted");
    }
}
