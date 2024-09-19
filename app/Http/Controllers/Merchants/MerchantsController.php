<?php

namespace App\Http\Controllers\Merchants;

use App\Enums\UserRole;
use App\Models\Merchant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Merchants\MerchantsRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Facade as Indonesia;

class MerchantsController extends Controller
{
    public function index(): View
    {
        return view('admin.merchants.index');
    }

    public function create(): View
    {
        return view('admin.merchants.create');
    }

    public function store(MerchantsRequest $request): RedirectResponse
    {
        $bannerImagePath = null;
        $isOfficial = $request->is_official === '1';
        $selectedUser = User::findOrFail($request->user_id);

        if ($selectedUser->role === UserRole::Admin) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'Admin users cannot be assigned as merchant.'
            ]);
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image');

            try {
                $bannerImagePath = $bannerImage->store('/images/merchants/banners', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading the banner image. Please try again.'
                ]);
            }
        }

        DB::transaction(function () use ($selectedUser, $request, $isOfficial, $bannerImagePath) {
            $selectedUser->fill(['role' => UserRole::Merchant])->save();

            $filteredSocialLinks = array_filter($request->social_links, function ($link) {
                return !empty($link['platform']) && !empty($link['link']);
            });

            $merchant = $selectedUser->merchant()->create([
                'name' => $request->name,
                'is_official' => $isOfficial,
                'banner_image' => $bannerImagePath ?? env('DEFAULT_MERCHANT_BANNER', 'images/merchants/banners/default.png'),
                'description' => $request->description,
                'phone' => $request->phone,
                'social_links' => $filteredSocialLinks,
            ]);

            $province = Indonesia::findProvince($request->province, null);
            $city = Indonesia::findCity($request->city, null);
            $district = Indonesia::findDistrict($request->district, null);
            $village = Indonesia::findVillage($request->village, null);

            $merchant->address()->create([
                'province' => $province->name,
                'city' => $city->name,
                'district' => $district->name,
                'village' => $village->name,
                'address_detail' => $request->address_detail,
                'postal_code' => $request->postal_code,
            ]);
        });

        if ($request->has('create_another')) {
            return redirect()->route('admin.merchants.create')->with('toast-notification', [
                'type' => 'success',
                'message' => "New merchant has been added! You can create another one.",
            ]);
        }

        $callbackUrl = $request->query('callbackUrl', route('admin.merchants.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "New merchant has been added!",
            ]);
    }

    public function edit(Merchant $merchant): View
    {
        return view('admin.merchants.edit', [
            'merchant' => $merchant,
        ]);
    }

    public function update(MerchantsRequest $request, Merchant $merchant): RedirectResponse
    {
        $bannerImagePath = $merchant->banner_image;
        $isOfficial = $request->is_official === '1';
        $selectedUser = User::findOrFail((int) $request->user_id);

        if ($selectedUser->role === UserRole::Admin) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'Admin users cannot be assigned as merchant.'
            ]);
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image');
            $defaultMerchantBanner = env('DEFAULT_MERCHANT_BANNER', 'images/merchants/banners/default.png');

            try {
                if ($bannerImagePath !== $defaultMerchantBanner && Storage::disk('public')->exists($bannerImagePath)) {
                    Storage::disk('public')->delete($bannerImagePath);
                }

                $bannerImagePath = $bannerImage->store('/images/merchants/banners', ['disk' => 'public']);
            } catch (\Exception $e) {
                return redirect()->back()->with('toast-notification', [
                    'type' => 'error',
                    'message' => 'There was an error uploading the banner image. Please try again.'
                ]);
            }
        }

        DB::transaction(function () use ($selectedUser, $request, $isOfficial, $bannerImagePath, $merchant) {
            if ($selectedUser->id !== $merchant->user_id) {
                $oldUser = User::find($merchant->user_id);

                if ($oldUser) {
                    $oldUser->update(['role' => UserRole::User]);
                }

                $selectedUser->update(['role' => UserRole::Merchant]);

                $merchant->user_id = $selectedUser->id;
            }

            $merchant->fill($request->validated());
            $merchant->is_official = $isOfficial;
            $merchant->banner_image = $bannerImagePath;
            $merchant->save();
        });

        $callbackUrl = $request->query('callbackUrl', route('admin.merchants.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'success',
                'message' => "The merchant has been updated!",
            ]);
    }

    public function destroy(Request $request, Merchant $merchant): RedirectResponse
    {
        try {
            $defaultMerchantBanner = env('DEFAULT_MERCHANT_BANNER', 'images/merchants/banners/default.png');
            $bannerImagePath = $merchant->banner_image;

            if ($bannerImagePath !== $defaultMerchantBanner && Storage::disk('public')->exists($bannerImagePath)) {
                Storage::disk('public')->delete($bannerImagePath);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast-notification', [
                'type' => 'error',
                'message' => 'There was an error deleting file for category. Please try again.'
            ]);
        }

        DB::transaction(function () use ($merchant) {
            $userMerchant = User::findOrFail($merchant->user_id);

            if ($userMerchant->role === UserRole::Merchant) {
                $userMerchant->update(['role' => UserRole::User]);
            }

            $merchant->delete();
        });

        $callbackUrl = $request->query('callbackUrl', route('admin.merchants.index'));

        return redirect($callbackUrl)
            ->with('toast-notification', [
                'type' => 'danger',
                'message' => "Merchant has been deleted!",
            ]);
    }
}
