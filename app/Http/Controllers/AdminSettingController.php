<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function holidays()
    {
        $holidays = Holiday::orderBy('date')->get();

        return view('admin.settings.holidays', compact('holidays'));
    }

    public function storeHoliday(Request $request)
    {
        $data = $request->validate([
            'date'        => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        Holiday::create($data);

        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroyHoliday(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('success', 'Hari libur dihapus.');
    }
}
