<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
            try{
       $validated = $request->validate([
            "name"=>["required","string","min:3",
            Rule::unique("products","name")
            ],
            "price"=>"required|numeric",
            "image_url"=>"required|image|mimes:jpg,png,jpeg,gif"
        ]);
        $imgPath = "";
        if($request->hasFile('image')){
            $imgPath = $request->file('image')->store('product','public');
        }
       $product = Product::create([
            "name"=>$validated["name"],
            "price"=>$validated["price"],
             "image_url"=>$imgPath
        ]);
        return response()->json([
            "date"=>$product,
        ]);
        }
    catch(Exception $error){
        return response()->json([
            "message"=>$error->getMessage(),
        ]);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        $product = Product::findOrFail($id);
     $validated = $request->validate([
            "name"=>["string","nullable","min:3","max:40",
            Rule::unique("products","name")->ignore($product->id)],
            "price"=>"nullable|numaric|decimal:0,2",
            "image"=>"nullable|image|mimes:jpeg,jpg,gif,png",
        ],
        [
            "name.min"=>"the name should be at least 3 chars",
            "name.string"=>"the name should be a text",
            "price.numeric"=>"the price field accepts only numeric",
            "image.mimes"=>"the file must be with jpeg,jpg,png,gif extenction"
        ]);
         $product->name = $validated["name"];
         $product->price = $validated["price"];
         $imgPath = "";
         if($request->hasFile('image')){
            if($product->image_url && Storage::disk('public')->exists($product->image_url)){
                Storage::disk('public')->delete($product->image_url);
            }
           $imgPath = $request->file('image')->store("product","public");
           $product->image_url = $imgPath;
         }
         $product->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
