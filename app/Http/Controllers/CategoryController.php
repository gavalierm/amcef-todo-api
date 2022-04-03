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
    public function index()
    {
        return Category::simplePaginate(1);
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

        return Category::updateOrCreate($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return App\Models\Category
     */
    public function show(Category $category, int $id)
    {
        return $category->findOrFail($id);
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
        //return abort(400,'Not updated');
        $status = $obj->update($request->all());

        if($status){
            return $category->findOrFail($id);
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
