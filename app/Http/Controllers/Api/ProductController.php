<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Retrieve all the data from table products
    public function index(){
        
        $products = Product::all();
        if($products->count() > 0){

            return response()->json([
                'status' => 200,
                'products' => $products
            ], 200);

        } else {

            return response()->json([
                'status' => 404,
                'products' => 'No data found'
            ], 404);

        }
    }

    // Create and store the new product data 
    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' =>       'required|string|max:191',
            'description' =>'required|string|max:191',
            'price' =>      'required|numeric|max:3000000',
            'stock' =>      'required|integer|max:5000000',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        } else {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);


            if($product){
                return response()->json([

                    'status' => 200,
                    'message'=> "Product has been added to the database"

                ],200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Error: Something went wrong | Product not added to the database"
                ],500);
            }
        }
    }

    // Retrieve a specific product
    public function edit($id){
        $product = Product::find($id);
        if($product){

            return response()->json([
                'status' => 200,
                'product' => $product
            ], 200);
            
        } else {
            
            return response()->json([
                'status' => 404,
                'message' => "Cannot find product"
            ],404);
        }
    }

    // Uodate the product 
    public function update(Request $request, int $id){

        $validator = Validator::make($request->all(), [
            'name' =>       'required|string|max:191',
            'description' =>'required|string|max:191',
            'price' =>      'required|numeric|max:3000000',
            'stock' =>      'required|integer|max:5000000',
        ]);


        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        } else {

            $product = Product::find($id);

            if($product){

                $product -> update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                ]);


                return response()->json([
                    'status' => 200,
                    'message'=> "Product has been updated"

                ],200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Error: Something went wrong | Product *NOT* updated"
                ],500);
            }
        }

    }

    // Delete the product from the database
    public function delete($id){

        $product = Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'status' => 200,
                'message'=> "Product has been deleted"

            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "The product does not exist"
            ], 404);
        }

    }




    public function getProductsByCategory($categoryName)
    {
        
        $products = DB::select("
            SELECT *
            FROM products
            WHERE id IN (
                SELECT product_id
                FROM categories
                WHERE category_name = ?
            )
        ", [$categoryName]);

        return response()->json($products);
    }
    





}
