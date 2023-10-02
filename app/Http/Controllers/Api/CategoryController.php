<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return new CategoryResource(true, 'List data categories' , $categories);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create category
        $category = Category::create([
            'name'     => $request->name,
        ]);

        //return response
        return new CategoryResource(true, 'Data Category Berhasil Ditambahkan!', $category);
    }

    
    public function show($id)
    {
        $category = Category::with('products')->find($id);
        return new CategoryResource(true, 'Detail Data Category!', $category);
    }

    
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::find($id);

            $category->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'content' => $request->content
            ]);

        //return response
        return new CategoryResource(true, 'Data Category Berhasil Diubah!', $category);
    }

    public function destroy($id)
    {

        //find post by ID
        $category = Category::find($id);

        //delete post
        $category->delete();

        //return response
        return new CategoryResource(true, 'Data Category Berhasil Dihapus!', null);
    }

}
