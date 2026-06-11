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
        try {
            $products = Product::get();
            $products->load('carts');
            return $this->successResponse(ProductResource::collection($products), 'successfully get product data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
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
        Log::info('image_url from frontend: ');
        Log::info($request->image_url);
        try {
            $checkProduct = Product::where('name', $request->name)->first();
            if($checkProduct) {
                throw new Exception('Product name already exists', 422);
            }
            
            Log::info('Product: ');
            Log::info($request);
            $this->authorize('create', Product::class);

            $imagePath = null;

            if($request->hasFile('image_url')) {
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
        try {
            return $this->successResponse(ProductResource::make($product), 'successfully displays product data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
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
            $this->authorize('create', $product);

            $checkProduct = Product::where('name', $request->name)->first();            

            $imagePath = $checkProduct->image_url;

            if($request->hasFile('image_url')) {
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
            $this->authorize('delete', $product);
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
