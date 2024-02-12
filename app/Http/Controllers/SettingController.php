<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = Setting::first();

        if($data->update($request->all())) {
            Alert::success('Success','Data Berhasil di Update');
            return redirect()->route('setting.index');
        }else {
            Alert::error('Error','Data Gagal di Update');
            return redirect()->route('setting.index');
        }
    }

    public function fetch()
    {
        $setting = Setting::first();
        return ResponseFormatter::success($setting, 'List Data Setting');
    }
}
