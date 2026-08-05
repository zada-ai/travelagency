<?php

namespace App\Http\Controllers;

use App\Models\VoucherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminVoucherSettingController extends Controller
{
    public function index()
    {
        $setting = VoucherSetting::first();

        return view('admin.voucher-management.index', compact('setting'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
            'max:5120',
            ],
        ]);

        $setting = VoucherSetting::first();

        if (!$setting) {
            $setting = new VoucherSetting();
        }

        /*
        |--------------------------------------------------------------------------
        | Company / Visa Provider Name
        |--------------------------------------------------------------------------
        */
        $setting->company_name = $request->company_name;

        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Old logo is NOT deleted.
        | This is required because old vouchers may still use it.
        |
        */
        if ($request->hasFile('logo')) {

            $directory = public_path('voucher-images');

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $filename = 'voucher-logo-' . time() . '-' . uniqid()
                . '.' . $request->file('logo')->extension();

            $request->file('logo')->move($directory, $filename);

            $setting->logo = 'voucher-images/' . $filename;
        }

        $setting->save();

        return redirect()
            ->back()
            ->with('success', 'Voucher provider settings updated successfully.');
    }
}