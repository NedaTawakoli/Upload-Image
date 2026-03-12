<?php

pricespace App\Http\Controllers;

use App\Models\Product;
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
