<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
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
    public function store(CartRequest $request)
    {
        try {
            $user = auth()->user()->id;

            $cart = Cart::create([
                'user_id'   =>  $user
            ]);

            $cart->products()->attach($request->product_id, [
                'qty'   =>  $request->qty
            ]);

            if(!$cart) {
                throw new Exception('failed to add Cart', 422);
            }

            $cart->load('products');

            return $this->successResponse(CartResource::make($cart), 'successfully displays Cart data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, Cart $cart)
    {
        try {
            if (!auth()->user()) {
                throw new Exception('Unauthorized', 401);
            }

            $cart->products()->updateExistingPivot($request->product_id, [
                'qty'   =>  $request->qty
            ]);

            if(!$cart) {
                throw new Exception('failed to add Cart', 422);
            }

            $cart->load('products');

            return $this->successResponse(CartResource::make($cart), 'successfully displays Cart data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $cart->products()->detach();
            $cart->delete();

            if(!$cart) {
                throw new Exception('failed to delete Cart', 422);
            }

            return $this->successResponse(null, 'successfully deleted Cart', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
