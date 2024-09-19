<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductsRequest;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index');
    }

    public function create(): View
    {
        $categories = Category::tree()->get()->toTree();

        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    public function store(ProductsRequest $request)
    {
        $images = collect($request->images)->filter(function ($image) {
            return isset($image['file']) && $image['file'] instanceof UploadedFile;
        });

        $productImagePayloads = [];

        try {
            foreach ($images as $image) {
                $path = $image['file']->store('/images/products', ['disk' => 'public']);
                $productImagePayloads[] = [
                    'image' => $path,
                    'is_main' => $image['is_main'] == '1',
                ];
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'danger',
                'message' => 'There was an error uploading one or more images. Please try again.'
            ]);
        }

        // Set Variant Is_Default


        DB::transaction(function () use ($request, $productImagePayloads) {
            $merchant = Merchant::findOrFail($request->merchant_id);
            $category = Category::findOrFail($request->category_id);

            if (isset($request->skus) && !empty($request->skus)) {
                $defaultSkuIndex = 0;
                foreach ($request->skus as $index => $skuData) {
                    if (isset($skuData['is_default']) && $skuData['is_default'] == '1') {
                        $defaultSkuIndex = $index;
                        break;
                    }
                }

                $product = new Product([
                    'name' => $request->name,
                    'description' => $request->description,
                    'has_variation' => true,
                ]);

                $product->merchant()->associate($merchant);
                $product->category()->associate($category);
                $product->save();

                foreach ($request->skus as $index => $skuData) {
                    $sku = $product->skus()->create([
                        'price' => $skuData['price'],
                        'stock' => $skuData['stock'],
                        'weight' => $skuData['weight'],
                        'sku' => $skuData['sku'],
                        'is_active' => $skuData['is_active'] == '1',
                        'is_default' => $defaultSkuIndex === $index,
                        'has_variation' => true,
                    ]);

                    foreach ($skuData['attribute_value'] as $attributeData) {
                        $attribute = ProductAttribute::firstOrNew(
                            ['name' => $attributeData['attribute'], 'product_id' => $product->id],
                            ['name' => $attributeData['attribute']]
                        );
                        $attribute->product_id = $product->id;
                        $attribute->save();

                        $sku->attributes()->attach($attribute->id, ['value' => $attributeData['value']]);
                    }
                }
            } else {
                $product = new Product([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'weight' => $request->weight,
                    'sku' => $request->sku,
                    'is_active' => $request->is_active == '1',
                    'has_variation' => false,
                ]);

                $product->merchant()->associate($merchant);
                $product->category()->associate($category);
                $product->save();

            }

            if (!empty($productImagePayloads)) {
                $product->images()->createMany($productImagePayloads);
            }
        });

        if ($request->has('create_another')) {
            return redirect()->route('admin.products.create')->with('toast-notification', [
                'type' => 'success',
                'message' => "New product has been added! You can create another one.",
            ]);
        }

        $callbackUrl = $request->query('callbackUrl', route('admin.products.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "New product has been added!",
            ]);
    }

    public function edit(Product $product)
    {
        $categories = Category::tree()->get()->toTree();
        $productImages = $product->images->map(function ($productImage) {
            return [
                'id' => $productImage->id,
                'image' => null,
                'isMain' => $productImage->is_main,
                'path' => Storage::url($productImage->image)
            ];
        })->toJson();

        // dd($productImages);
        return view('admin.products.edit', [
            'categories' => $categories,
            'product' => $product,
            'productImages' => $productImages,
        ]);
    }

    public function update(ProductsRequest $request, Product $product)
    {
        $images = collect($request->images)->filter(function ($image) {
            return (isset($image['file']) && $image['file'] instanceof UploadedFile) || (isset($image['path']) && $image['path']);
        });

        $productImagePayloads = [];
        $newImagePaths = [];
        $existingImagePaths = $product->images()->pluck('image')->toArray();

        try {
            foreach ($images as $image) {
                if (isset($image['file']) && $image['file'] instanceof UploadedFile) {
                    $path = $image['file']->store('/images/products', ['disk' => 'public']);
                } else {
                    $path = str_replace('/storage/', '', $image['path']);
                }

                $newImagePaths[] = $path;
                $productImagePayloads[] = [
                    'image' => $path,
                    'is_main' => $image['is_main'] == '1',
                ];
            }

            $imagesToDelete = array_diff($existingImagePaths, $newImagePaths);

            foreach ($imagesToDelete as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $product->images()->where('image', $imagePath)->delete();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error uploading one or more images. Please try again.'
            ]);
        }

        DB::transaction(function () use ($request, $product, $productImagePayloads) {
            if ($product->category_id !== (int) $request->category_id) {
                $selectedCategory = Category::findOrFail((int) $request->category_id);

                $product->category()->associate($selectedCategory);
                var_dump("CATEGORY BERUBAH");
            }

            if ($product->merchant_id !== (int) $request->merchant_id) {
                $selectedMerchant = Merchant::findOrFail((int) $request->merchant_id);

                $product->merchant()->associate($selectedMerchant);
            }

            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $request->stock,
                'is_active' => $request->is_active == '1',
            ]);

            if (!empty($productImagePayloads)) {
                foreach ($productImagePayloads as $payload) {
                    $product->images()->updateOrCreate(
                        ['image' => $payload['image']],
                        ['is_main' => $payload['is_main']]
                    );
                }
            }
        });

        return redirect()->route('admin.products.index')->with('toast-notification', [
            'type' => 'success',
            'message' => "Product has been updated!",
        ]);
    }


    public function destroy(Request $request, Product $product)
    {
        try {
            $productImagePaths = $product->images()->pluck('image')->toArray();

            foreach ($productImagePaths as $imagePath) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                   Storage::disk('public')->delete($imagePath);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error deleting file for category. Please try again.'
            ]);
        }

        $product->delete();
        $callbackUrl = $request->query('callbackUrl', route('admin.products.categories.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'danger',
                'message' => "Product has been deleted!",
            ]);
    }
}
