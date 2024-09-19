<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;

use App\Http\Requests\Categories\CategoriesRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Category::class);
        return view('admin.products.categories.index');
    }


    public function create(): View
    {
        Gate::authorize('create', Category::class);
        return view('admin.products.categories.create', [
            'categories' => Category::tree()->get()->toTree(),
        ]);
    }

    public function store(CategoriesRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $imagePath = null;
        $iconPath = null;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imagePath = $image->store('/images/categories/images', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading image. Please try again.'
                ]);
            }
        }

        if ($request->hasFile('icon')) {
            try {
                $icon = $request->file('icon');
                $iconPath = $icon->store('/images/categories/icons', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading icon. Please try again.'
                ]);
            }
        }

        $featured = $request->featured === '1';

        Category::create([
            ...$request->validated(),
            'featured' => $featured,
            'image' => $imagePath,
            'icon' => $iconPath,
            'slug' => str()->slug($request->name),
        ]);

        if ($request->has('create_another')) {
            return redirect()->route('admin.products.categories.create')->with('toast-notification', [
                'type' => 'success',
                'message' => "New category has been added! You can create another one.",
            ]);
        }

        $callbackUrl = $request->query('callbackUrl', route('admin.products.categories.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "New category has been added!",
            ]);
    }

    public function edit(Category $category): View
    {
        Gate::authorize('update', Category::class);

        return view('admin.products.categories.edit', [
            'category' => $category,
            'categories' => Category::tree()->get()->toTree(),
        ]);
    }

    public function update(CategoriesRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', Category::class);

        $imagePath = $category->image;
        $iconPath = $category->icon;

        if ($request->hasFile('image')) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $image = $request->file('image');
                $imagePath = $image->store('/images/categories/images', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading image. Please try again.'
                ]);
            }
        }

        if ($request->hasFile('icon')) {
            try {
                if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                    Storage::disk('public')->delete($iconPath);
                }

                $icon = $request->file('icon');
                $iconPath = $icon->store('/images/categories/icons', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading icon. Please try again.'
                ]);
            }
        }

        $featured = $request->featured == '1';
        $newSlug = strtolower($request->name) !== strtolower($category->name)
            ? str()->slug($request->name)
            : $category->slug;

        $category->update([
            ...$request->validated(),
            'image' => $imagePath,
            'icon' => $iconPath,
            'featured' => $featured,
            'slug' => $newSlug,
        ]);

        $callbackUrl = $request->query('callbackUrl', route('admin.products.categories.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "Category has been updated!",
            ]);
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('delete', Category::class);

        try {
            $imagePath = $category->image;
            $iconPath = $category->icon;

            if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                Storage::disk('public')->delete($iconPath);
            }

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error deleting file for category. Please try again.'
            ]);
        }

        $category->delete();
        $callbackUrl = $request->query('callbackUrl', route('admin.products.categories.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'danger',
                'message' => "Category has been deleted!",
            ]);
    }
}
