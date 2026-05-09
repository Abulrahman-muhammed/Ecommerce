<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\ImageUploadService;
class CustomerController extends Controller
{
    protected $imageUploadService;
    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $customers = User::query()
            ->filter($request->only(['search', 'role', 'verified', 'provider']))
            ->latest()
            ->paginate(15)
            ->withQueryString();
 
        return view('admin.customers.index', compact('customers'));
    }

 
    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        $orders = $customer->orders()
                            ->withCount('items')
                            ->latest()
                            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'orders'));
    }
 
    /**
     * Show the form for editing the customer.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, User $customer)
    {
        $data = $request->validated();
        // Update password only if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
 
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                $this->imageUploadService->delete($customer->avatar);
            }
            $data['avatar'] = $this->imageUploadService->upload($request->file('avatar'), 'avatars');
        }
 
        $customer->update($data);
 
        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer updated successfully.');
    }
 
    /**
     * Soft-delete (trash) the customer.
     */
    public function destroy(User $customer)
    {
        $customer->delete();
 
        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer moved to trash.');
    }
 
    /**
     * List trashed customers.
     */
    public function trashed(Request $request)
    {
        $customers = User::onlyTrashed()
            ->filter($request->only(['search', 'role', 'verified', 'provider']))
            ->latest('deleted_at')
            ->paginate(15)
            ->withQueryString();
 
        return view('admin.customers.trashed', compact('customers'));
    }
 
    /**
     * Restore a trashed customer.
     */
    public function restore($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);
        $customer->restore();
 
        return redirect()->route('admin.customers.trashed')
                         ->with('success', 'Customer restored successfully.');
    }
 
    /**
     * Permanently delete a trashed customer.
     */
    public function forceDelete($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);

        if ($customer->avatar) {
            $this->imageUploadService->delete($customer->avatar);
        }

        $customer->forceDelete();

        return redirect()->route('admin.customers.trashed')
                        ->with('success', 'Customer permanently deleted.');
    }


}
