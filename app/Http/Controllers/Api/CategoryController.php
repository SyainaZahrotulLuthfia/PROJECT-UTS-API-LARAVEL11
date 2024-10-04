<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all category
        $category = Category::latest()->paginate(5);

        //response
        $response = [
            'status' => 'Success',
            'message' => 'List All Category',
            'data' => $category,
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
            'category' => 'required|unique:categories|min:2',
        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Field',
                'message' => 'Invalid Field',
                'errors' => $validator->errors()
            ],422);
        }


        //jika validasi sukses masukan data ke database
        $category = Category::create([
            'category' => $request->category,
        ]);


        //response
        $response = [
            'status'   => 'Success',
            'message'   => 'Add Category Success',
            'data'      => $category,
        ];


        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find category by ID
        $category = Category::find($id);


        //response
        $response = [
            'status'   => 'Success',
            'message'   => 'Detail Category Found',
            'data'      => $category,
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
            'category' => 'required|unique:categories|min:2',

        ]);


        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        //find categoty by ID
        $category = Category::find($id);


        $category->update([
            'category' => $request->category,

        ]);


        //response
        $response = [
            'status' => 'Success',
            'message'   => 'Update Category Success',
            'data'      => $category,
        ];


        return response()->json($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find chategory by ID
        $category = Category::find($id)->delete();
        $response = [
            'status' => 'Success',
            'message'   => 'Delete Category Success',        ];
        return response()->json($response, 200);
    }
}
