<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentStatusEnum;
use App\Enums\OrderStatusEnum;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();

        $signature = $request->header(
            'Stripe-Signature'
        );

        $secret = config(
            'services.stripe.webhook_secret'
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | Verify Webhook Signature
            |--------------------------------------------------------------------------
            */

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $secret
            );

        } catch (
            UnexpectedValueException $e
        ) {

            return response()->json([
                'message' => 'Invalid payload'
            ], 400);

        } catch (
            SignatureVerificationException $e
        ) {

            return response()->json([
                'message' => 'Invalid signature'
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Handle Event
        |--------------------------------------------------------------------------
        */

        switch ($event->type) {

            /*
            |--------------------------------------------------------------------------
            | Payment Success
            |--------------------------------------------------------------------------
            */

            case 'checkout.session.completed':

                $session = $event->data->object;

                $this->handleCheckoutSessionCompleted(
                    $session
                );

                break;
        }

        return response()->json([
            'success' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Successful Payment
    |--------------------------------------------------------------------------
    */

    protected function handleCheckoutSessionCompleted(
        $session
    ): void {

        DB::transaction(function () use ($session) {

            /*
            |--------------------------------------------------------------------------
            | Find Order
            |--------------------------------------------------------------------------
            */

            $order = Order::where(
                'stripe_session_id',
                $session->id
            )->first();

            if (! $order) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Processing
            |--------------------------------------------------------------------------
            */

            if (
                $order->payment_status
                === PaymentStatusEnum::PAID
            ) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Order
            |--------------------------------------------------------------------------
            */

            $order->update([

                'payment_status' =>
                    PaymentStatusEnum::PAID,

                'status' =>
                    OrderStatusEnum::PROCESSING,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $order->payment()->update([

                'status' =>
                    PaymentStatusEnum::PAID,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Decrease Stock
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $product = $item->product;

                if (
                    $product->quantity
                    < $item->quantity
                ) {

                    throw new \Exception(
                        "{$product->name} out of stock."
                    );
                }

                $product->decrement(
                    'quantity',
                    $item->quantity
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            auth()->user()?->cartItems()->delete();
        });
    }
}
