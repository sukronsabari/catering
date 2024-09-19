<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileImageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileImageController extends Controller
{
    public function update(ProfileImageRequest $request): RedirectResponse
    {
        if (!$request->hasFile('image')) {
            return redirect()->back();;
        }

        try {
            $user = $request->user();
            $imagePath = $user->image;
            $defaultProfile = env('DEFAULT_IMAGE_PROFILE', 'images/users/default.png');

            if ($imagePath !== $defaultProfile && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $image = $request->file('image');
            $imagePath = $image->store('/images/users', ['disk' => 'public']);

            $user->image = $imagePath;
            $user->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error uploading the banner image. Please try again.'
            ]);
        }

        return redirect()->back()->with("toast-notification", [
            "type" => "success",
            "message" => "Update profile successfully!"
        ]);
    }
}
