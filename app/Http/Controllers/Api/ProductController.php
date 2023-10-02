<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(5);

        // return $products;
        return new ProductResource(true, 'List data products', $products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'required|image',
            'content' => 'required',

        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());


        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $image->hashName(),
            'content' => $request->content
        ]);

        return new ProductResource(true, 'Data Product berhasil ditambahkan', $product);
    }


    public function show($id)
    {
        $product = Product::with('category')->find($id);
        return new ProductResource(true, 'Detail Data Product!', $product);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'nullable|image',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            Storage::delete('public/products/' . basename($product->image));

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'image' => $image->hashName(),
                'content' => $request->content
            ]);
        } else {
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'content' => $request->content
            ]);
        }
        // dd($request)
        // return $request->category_id;

        //return response
        return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
    }


    public function destroy($id)
    {

        //find product by ID
        $product = Product::find($id);

        //delete image
        Storage::delete('public/products/'.basename($product->image));

        //delete product
        $product->delete();

        //return response
        return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
    }
}
