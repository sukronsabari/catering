<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.settings.edit', [
            'user' => $request->user(),
        ]);
    }
}
