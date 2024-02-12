<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('roles',['USER','SALES'])->get();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
            'email' => 'unique:users,email',
            'password' => 'min:6',
            ],
            [
                'email.unique' => 'Email Sudah Terdaftar',
                'password.min' => 'Password Minimal 6 Karakter',
            ]
        );

        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->phone = $request->phone;
        $data->roles = $request->roles;
        $data->save();

        if($data != null) {
            Alert::success('Success','Data Berhasil di Tambah');
            return redirect()->route('users.index');
        }else {
            Alert::error('Error','Data Gagal di Tambah');
            return redirect()->route('users.index');
        }
    }
    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->roles = $request->roles;
        $data->save();
        if($data != null) {
            Alert::success('Success','Data Berhasil di Update');
            return redirect()->route('users.index');
        }else {
            Alert::error('Error','Data Gagal di Update');
            return redirect()->route('users.index');
        }
    }

    public function delete($id)
    {
        $data = User::findOrFail($id);
        if($data != null) {
            $data->delete();
            Alert::success('Success','Data Berhasil di Hapus');
            return redirect()->route('users.index');
        }else {
            Alert::error('Error','Data Gagal di Hapus');
            return redirect()->route('users.index');
        }

    }
}
