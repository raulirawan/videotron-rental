<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return view('category.index', compact('category'));
    }

    public function store(Request $request)
    {
        $data = new Category();
        $data->name = $request->name;
        $data->save();

        if($data != null) {
            Alert::success('Success','Data Berhasil di Tambah');
            return redirect()->route('category.index');
        }else {
            Alert::error('Error','Data Gagal di Tambah');
            return redirect()->route('category.index');
        }
    }
    public function update(Request $request, $id)
    {
        $data = Category::findOrFail($id);
        $data->name = $request->name;
        $data->save();
        if($data != null) {
            Alert::success('Success','Data Berhasil di Update');
            return redirect()->route('category.index');
        }else {
            Alert::error('Error','Data Gagal di Update');
            return redirect()->route('category.index');
        }
    }

    public function delete($id)
    {
        $data = Category::findOrFail($id);
        if($data != null) {
            $data->delete();
            Alert::success('Success','Data Berhasil di Hapus');
            return redirect()->route('category.index');
        }else {
            Alert::error('Error','Data Gagal di Hapus');
            return redirect()->route('category.index');
        }

    }
}
