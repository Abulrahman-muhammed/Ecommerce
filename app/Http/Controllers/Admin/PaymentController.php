<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Enums\PaymentStatusEnum;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = Payment::with(['order', 'user'])
            ->search($request->search)
            ->byStatus($request->status)
            ->byMethod($request->method)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments'       => $payments,
            'paymentStatuses' => PaymentStatusEnum::cases(),
            'methods'        => ['cash', 'stripe'],
        ]);    
        }

    public function destroy(Payment $payment)
    {
        $payment->delete();
 
        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment moved to trash.');
    }

    /* ------------------------------------------------------------------ */
    /*  Trashed                                                             */
    /* ------------------------------------------------------------------ */
 
    public function trashed()
    {
        $payments = Payment::onlyTrashed()
            ->with(['order', 'user'])
            ->latest('deleted_at')
            ->paginate(15);
 
        return view('admin.payments.trashed', [
            'payments' => $payments,
        ]);
    }
 
    public function restore(int $id)
    {
        Payment::onlyTrashed()->findOrFail($id)->restore();
 
        return redirect()
            ->route('admin.payments.trashed')
            ->with('success', 'Payment restored.');
    }
 
    public function forceDelete(int $id)
    {
        Payment::onlyTrashed()->findOrFail($id)->forceDelete();
 
        return redirect()
            ->route('admin.payments.trashed')
            ->with('success', 'Payment permanently deleted.');
    }
}
