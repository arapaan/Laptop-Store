<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

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
            $this->authorize('create', Cart::class);

            $user = auth()->user()->id;
            $cart = Cart::where('user_id', $user)->first();

            if(!$cart) {
                $cart = Cart::create(['user_id'   =>  $user]);
            }           

            // if ($request->qty > 1) {
            //     $product  = Product::find($request->product_id);

            //     if ($product->stock < $request->qty) {
            //         throw new Exception('This ' . $product->name . ' below the required amount, only ' . $product->stock . ' remains.', 422);
            //     }

            //     $product->update([
            //         'stock' =>  $product->stock - $request->qty
            //     ]);
            // }

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
    public function show(Cart $cart)
    {
        try {
            return $this->successResponse(CartResource::make($cart), 'successfully displays cart data', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
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
            $this->authorize('create', $cart);

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
            $this->authorize('delete', $cart);

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

    public function checkout(Cart $cart)
    {
        try {
            Stripe::setApiKey(config('stripe.sk'));
            
            $datas = $cart->products;

            // dd($datas);            
            $lineItems = [];
            foreach ($datas as $data) {
                $lineItems[] = [
                'price_data' => [
                    'currency' => 'idr',
                    'product_data' => [
                        'name' => $data['name'],
                    ],
                    'unit_amount' => $data['price'],
                ],
                'quantity' => $data['pivot']->qty,
            ];
            }
            
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // 'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                // 'cancel_url' => route('payment.cancel'),
                'success_url' => route('success'),
                'cancel_url' => route('index'),
            ]);

            return redirect()->away($session->url);

            return $this->successResponse(CartResource::make($query), 'successfully deleted Cart', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
