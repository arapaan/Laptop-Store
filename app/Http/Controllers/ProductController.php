<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(ProductRequest $request)
    {
        try {
            if (!auth()->user()) {
                throw new Exception('Unauthorized', 401);
            }

            $imagePath = null;

            if($request->image_url) {
                $imagePath = $request->file('image_url')->store('laptops', 'public');
            }

            $created = Product::create([
                'name'          =>  $request->name,
                'description'   =>  $request->description,
                'price'         =>  $request->price,
                'stock'         =>  $request->stock,
                'image_url'     =>  $imagePath,
            ]);

            if(!$created) {
                throw new Exception('failed to add Product', 422);
            }

            return $this->successResponse(ProductResource::make($created), 'successfully displays Product data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            // dd($product);

            $imagePath = null;

            if($request->image_url) {
                $imagePath = $request->file('image_url')->store('laptops', 'public');
            }

            $updated = $product->update([
                'name'          =>  $request->name,
                'description'   =>  $request->description,
                'price'         =>  $request->price,
                'stock'         =>  $request->stock,
                'image_url'     =>  $imagePath,
            ]);
            
            if(!$updated) {
                throw new Exception('failed to update the Product', 422);
            }

            return $this->successResponse(ProductResource::make($product), 'successfully to update the Product', 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $deleted = $product->delete();

            if(!$deleted) {
                throw new Exception('failed to delete the Product', 422);
            }

            return $this->successResponse(null, 'Successfully to delete the Product', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getMessage());
        }
    }
}
