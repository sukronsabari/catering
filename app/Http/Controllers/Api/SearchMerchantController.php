<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;

class SearchMerchantController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query dari parameter pencarian
        // $search = $request->query('search');

        // // Query database untuk daftar merchant yang cocok dengan pencarian
        // $merchants = Merchant::query()
        //     ->where('name', 'like', "%{$search}%")
        //     ->limit(10)  // Batasi hasil untuk mencegah terlalu banyak data
        //     ->get(['id', 'name']);  // Hanya ambil kolom yang diperlukan

        return response()->json([]);
    }
}
