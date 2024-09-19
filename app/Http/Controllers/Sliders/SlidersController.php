<?php

namespace App\Http\Controllers\Sliders;

use App\Http\Requests\Sliders\SlidersRequest;
use App\Models\Slider;
use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SlidersController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Slider::class);
        return view('admin.sliders.index');
    }

    public function create(): View
    {
        Gate::authorize('create', Slider::class);
        return view('admin.sliders.create');
    }

    public function store(SlidersRequest $request): RedirectResponse
    {
        Gate::authorize('create', Slider::class);

        $imagePath = null;
        $isActive = $request->is_active === '1';
        try {
            $image = $request->file('image');
            $imagePath = $image->store('/images/sliders', ['disk' => 'public']);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error uploading the banner image. Please try again.'
            ]);
        }

        Slider::create([...$request->validated(), 'image' => $imagePath, 'is_active' => $isActive]);

        if ($request->has('create_another')) {
            return redirect()->route('admin.sliders.create')->with('toast-notification', [
                'type' => 'success',
                'message' => "New slider has been added! You can create another one.",
            ]);
        }

        $callbackUrl = $request->query('callbackUrl', route('admin.sliders.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "New slider has been added!",
            ]);
    }

    public function edit(Slider $slider): View
    {
        Gate::authorize('update', Slider::class);
        return view('admin.sliders.edit', [
            'slider' => $slider,
        ]);
    }

    public function update(SlidersRequest $request, Slider $slider): RedirectResponse
    {
        Gate::authorize('update', Slider::class);

        $imagePath = $slider->image;
        $isActive = $request->is_active === '1';

        if ($request->hasFile('image')) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $image = $request->file('image');
                $imagePath = $image->store('/images/sliders', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading the banner image. Please try again.'
                ]);
            }
        }

        $slider->update([...$request->validated(), 'image' => $imagePath, 'is_active' => $isActive]);

        $callbackUrl = $request->query('callbackUrl', route('admin.sliders.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "Slider has been updated!",
            ]);
    }

    public function destroy(Request $request, Slider $slider): RedirectResponse
    {
        Gate::authorize("delete", Slider::class);

        try {
            $imagePath = $slider->image;

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error deleting file for category. Please try again.'
            ]);
        }

        $slider->delete();
        $callbackUrl = $request->query('callbackUrl', route('admin.sliders.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'danger',
                'message' => "Slider has been deleted!",
            ]);
    }
}
