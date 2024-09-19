<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchMerchantsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('query');  // Mengambil parameter pencarian

        $merchants = Merchant::where('name', 'like', "%{$search}%")
            ->limit(10) // Batasi jumlah hasil
            ->get(['id', 'name']); // Hanya ambil kolom id dan name

        return response()->json($merchants);
    }
}
