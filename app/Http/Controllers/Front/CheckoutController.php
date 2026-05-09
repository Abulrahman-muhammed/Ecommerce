<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use App\Http\Requests\StoreChecoutRequest;
use App\Services\StripeCheckoutService;
use App\Repositories\cart\CartRepositoryInterface;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService,
        protected StripeCheckoutService $stripeCheckoutService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Checkout Page
    |--------------------------------------------------------------------------
    */

    public function index(
        CartRepositoryInterface $cartRepo
    ) {

        return view('front.checkout.index', [

            'cartItems' => $cartRepo->getCartItems(),

            'total' => $cartRepo->getCartTotal(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Order
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreChecoutRequest $request,
        CartRepositoryInterface $cartRepo
    ) {

        try {

            switch ($request->payment_method) {

                /*
                |--------------------------------------------------------------------------
                | Cash On Delivery
                |--------------------------------------------------------------------------
                */

                case 'cash':

                    $order = $this->checkoutService
                        ->placeOrder(
                            $request->validated(),
                            $cartRepo
                        );

                    return redirect()
                        ->route(
                            'front.orders.show',
                            $order
                        )
                        ->with(
                            'success',
                            'Order placed successfully.'
                        );

                /*
                |--------------------------------------------------------------------------
                | Stripe Checkout
                |--------------------------------------------------------------------------
                */

                case 'stripe':

                    $response = $this->stripeCheckoutService
                        ->create(
                            $request->validated(),
                            $cartRepo
                        );

                    return redirect($response['url']);

                default:

                    throw new \Exception(
                        'Invalid payment method.'
                    );
            }

        } catch (\Exception $e) {

            Log::error(
                'Checkout Error: ' . $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}