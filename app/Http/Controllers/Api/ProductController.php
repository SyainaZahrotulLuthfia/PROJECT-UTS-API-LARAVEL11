<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $produk = Product::latest()->paginate(5);
        $produk = Category::with(relations : ['products'])->latest()->paginate(5);

        $response = [
            'status' => 'Success',
            'message' => 'List All Product',
            'data' => $produk,
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi data
        $validator = Validator::make($request->all(),[
            'category_id' =>    'required',
            'product' =>        'required|min:2|unique:products',
            'description' =>    'required',
            'price' =>          'required|integer',
            'stock' =>          'required|integer',
            'image' =>          'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Faild',
                'message' => 'Invalid Field',
                'errors' => $validator->errors()
            ],422);
        }


        //upload image
        $image = $request->file('image');
        $image->storeAs('public/product', $image->hashName());

        //jika validasi sukses masukan data produk ke database
        $produk = Product::create([
            'category_id' => $request->category_id,
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image->hashName(),
        ]);


        //response
        $response = [
            'status' => 'Success',
            'message'   => 'Add Product Success',
            'data'      => $produk,
        ];


        return response()->json($response, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Category::with(relations : ['products'])->latest()->paginate($id);

        //response
        $response = [
            'status'   => 'Success',
            'message'   => 'Detail Product Found',
            'data'      => $produk,
        ];


        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Faild',
                'message' => 'Invalid Field',
                'errors' => $validator->errors()
            ],422);
        }


        //find level by ID
        $produk = Product::find($id);

         //check if image is not empty
        if ($request->hasFile('image')) {


            //upload image
            $image = $request->file('image');
            $image->storeAs('public/product', $image->hashName());


            //delete old image
            Storage::delete('public/product/' . basename($produk->image));


            //update post with new image
            $produk->update([
                'category_id' => $request->category_id,
                'product' => $request->product,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $image->hashName(),
            ]);

        } else {
            //update post without image
            $produk->update([
                'category_id' => $request->category_id,
                'product' => $request->product,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
        }

        //response
        $response = [
            'status' => 'Success',
            'message'   => 'Update Product Success',
            'data'      => $produk,
        ];


        return response()->json($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Product::find($id);

        if (isset($produk)) {
            //jika data ditemukan delete image from storage
            Storage::delete('public/product/'.basename($produk->image));


            //delete post
            $produk->delete();


            $response = [
                'status'   => 'Success',
                'success'   => 'Delete Product Success',
            ];
            return response()->json($response, 200);


        } else {
            //jika data tidak ditemukan
            $response = [
                'status'   => 'Failed',
                'success'   => 'Data Product Not Found',
            ];


            return response()->json($response, 404);


        }

    }
}
