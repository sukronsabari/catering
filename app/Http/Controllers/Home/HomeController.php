<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('is_active', true)->latest()->get();
        $categories = Category::all();

        return view('home.index', [
            'sliders' => $sliders,
            'categories' => $categories,
        ]);
    }
}
