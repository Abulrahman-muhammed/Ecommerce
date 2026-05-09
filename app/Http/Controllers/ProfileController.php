<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    public function edit(): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.edit', compact('admin'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $data  = $request->validated();

        // ── Image ──────────────────────────────────
        if ($request->boolean('remove_image')) {
            $this->deleteImage($admin);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($admin);
            $data['image'] = $this->imageService->upload(
                $request->file('image'),
                'admins'
            );
        }

        unset($data['remove_image']);

        $admin->update($data);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    private function deleteImage($admin): void
    {
        if (! $admin->image) {
            return;
        }

        $this->imageService->delete($admin->image);
        $admin->image = null;
    }
}