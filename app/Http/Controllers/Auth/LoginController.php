<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;

use App\Http\Controllers\Auth\loginController;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
  
    use AuthenticatesUsers;
  
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Create a new controller instance.
     *
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
{
    $input = $request->all();

    $this->validate($request, [
        'nim' => 'required',
        'password' => 'required',
        'role' => 'required'
    ]);

    // Temukan pengguna berdasarkan NIM
    $user = User::where('nim', $input['nim'])->first();

    if ($user) {
        // Pengguna dengan NIM ditemukan

        if ($user->role == $input['role']) {
            // Peran pengguna sesuai dengan yang diminta

            if (auth()->attempt(array('nim' => $input['nim'], 'password' => $input['password']))) {
                // Pengguna berhasil login berdasarkan nim dan password
                if ($input['role'] == 'mahasiswa') {
                    return redirect()->route('home');
                } elseif ($input['role'] == 'dosen') {
                    return redirect()->route('dosen.home');
                } elseif ($input['role'] == 'koordinator') {
                    return redirect()->route('koor.home');
                } elseif ($input['role'] == 'admin') {
                    return redirect()->route('admin.home');
                }
            } else {
                // Gagal login
                return redirect()->route('login')
                    ->with('error', 'NIM Atau Password Anda Salah.');
            }
        } else {
            // Peran pengguna tidak sesuai dengan yang diminta
            return redirect()->route('login')
                ->with('error', 'Anda tidak bisa login sebagai ' . $input['role']);
        }
    } else {
        // Pengguna dengan NIM tidak ditemukan
        return redirect()->route('login')
            ->with('error', 'NIM tidak ditemukan.');
    }
}


}
