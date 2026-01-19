<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function store(Request $request){
        $id = $request->id;
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
        ],[
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        $newRequest = $request->all();
        if(!$id){
            $newRequest['password'] = Hash::make('12345678');
        }

        user::updateOrCreate(['id' => $id], $newRequest);

        toast()->success('User berhasil Di simpan');

        return redirect()->route('users.index');
    }
}
