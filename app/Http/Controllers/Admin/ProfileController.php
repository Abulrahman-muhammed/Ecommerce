<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\Intl\Countries;

class ProfileController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    // ──────────────────────────────────────────────
    //  Show
    // ──────────────────────────────────────────────

    public function edit(): View
    {
        $user    = Auth::user()->load('profile.avatarImage');
        $profile = $user->profile;
        $countries = Countries::getNames();
        return view('admin.profile.edit', compact('user', 'profile', 'countries'));
    }

    // ──────────────────────────────────────────────
    //  Update
    // ──────────────────────────────────────────────

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $profile = Auth::user()->load('profile.avatarImage')->profile;
        $data    = $request->validated();

        // ── Avatar ─────────────────────────────────
        if ($request->boolean('remove_avatar')) {
            $this->deleteAvatar($profile);
        }

        if ($request->hasFile('avatar')) {
            $this->deleteAvatar($profile);

            $path = $this->imageService->upload(
                $request->file('avatar'),
                'avatars'
            );

            $profile->avatarImage()->updateOrCreate(
                ['usage' => 'avatar'],
                ['path'  => $path]
            );

            $profile->unsetRelation('avatarImage');
        }

        unset($data['avatar'], $data['remove_avatar']);

        $profile->update($data);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // ──────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────

    private function deleteAvatar($profile): void
    {
        $image = $profile->relationLoaded('avatarImage')
            ? $profile->avatarImage
            : $profile->avatarImage()->first();

        if (! $image) {
            return;
        }

        $this->imageService->delete($image->path);
        $image->delete();

        $profile->unsetRelation('avatarImage');
    }
}