<?php

namespace App\Http\Controllers;

use App\Models\Videotron;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VideotronController extends Controller
{
    public function index()
    {
        $videotron = Videotron::all();
        return view('videotron.index', compact('videotron'));
    }

    public function store(Request $request)
    {
        $data = new Videotron();
        $data->name = $request->name;
        $data->category_id = $request->category_id;
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $tujuan_upload = 'image/videotron/';
            $nama_file = time()."_".$file->getClientOriginalName();
            $nama_file = str_replace(' ', '', $nama_file);
            $file->move($tujuan_upload,$nama_file);

            $data->image = $tujuan_upload.$nama_file;
        }
        $data->save();

        if($data != null) {
            Alert::success('Success','Data Berhasil di Tambah');
            return redirect()->route('videotron.index');
        }else {
            Alert::error('Error','Data Gagal di Tambah');
            return redirect()->route('videotron.index');
        }
    }
    public function update(Request $request, $id)
    {
        $data = Videotron::findOrFail($id);
        $data->name = $request->name;
        $data->category_id = $request->category_id;
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $tujuan_upload = 'image/videotron/';
            $nama_file = time()."_".$file->getClientOriginalName();
            $nama_file = str_replace(' ', '', $nama_file);
            $file->move($tujuan_upload,$nama_file);
            if(file_exists($data->image)) {
                unlink($data->image);
            }
            $data->image = $tujuan_upload.$nama_file;
        }
        $data->save();
        if($data != null) {
            Alert::success('Success','Data Berhasil di Update');
            return redirect()->route('videotron.index');
        }else {
            Alert::error('Error','Data Gagal di Update');
            return redirect()->route('videotron.index');
        }
    }

    public function delete($id)
    {
        $data = Videotron::findOrFail($id);
        if($data != null) {
            if(file_exists($data->image)) {
                unlink($data->image);
            }
            $data->delete();
            Alert::success('Success','Data Berhasil di Hapus');
            return redirect()->route('videotron.index');
        }else {
            Alert::error('Error','Data Gagal di Hapus');
            return redirect()->route('videotron.index');
        }

    }
}
