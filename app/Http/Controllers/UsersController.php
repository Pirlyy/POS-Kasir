<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    public function gantipassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
            //'password'     => [password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ], [
            'old_password.required' => 'Password Lama Wajib Diisi',
            'password.required' => 'Password Baru harus diisi',
            'password.min' => 'password minimal 8 karakter',
            'password.confirmed' => 'password baru tidak sama dengan konfirmasi',
        ]);

        $user = User::find(Auth::id());

        //kodingan untuk cek PW old

        if(!Hash::check($request->old_password, $user->password)){
            toast()->error('Password Tidak Sesuai');
            return redirect()->route('dashboard');
        }

        //kodingan update

        $user->update([
            'password' => Hash::make($request->password)

        ]);

        toast()->success('Password Berhasil Diubah');
            return redirect()->route('dashboard');
    }

    public function destroy(String $id){
        $user = user::find($id);

        if (Auth::id() == $id){
            toast()->error('tidak dapat menghapus akun yang lagi login');
            return redirect()->route('users.index');
        }

        $user->delete();
        toast()->success('sudah berhasil dihapus');
            return redirect()->route('users.index');
    }

    public function resetpassword(Request $request){
        $request->validate([
            'id'        => 'required'
        ]);

        $user = User::find($request->id);
        $user->update([
            'password' => Hash::make('12345678')
        ]);

        toast()->success('Password Berhasil di riset');
            return redirect()->route('users.index');
    }

}
