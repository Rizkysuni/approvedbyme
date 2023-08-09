<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sempro;
use App\Models\User;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $sempros = Sempro::all();
        return view('home', compact('sempros'));
    } 
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dosenHome(): View
    {
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem1', $dosenId)
                    ->orWhere('dospem2', $dosenId)
                    ->orWhere('penguji1', $dosenId)
                    ->orWhere('penguji2', $dosenId)
                    ->orWhere('penguji3', $dosenId)
                    ->get();

        return view('dosenHome', compact('sempros'));
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function koorHome(): View
    {
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem1', $dosenId)
                    ->orWhere('dospem2', $dosenId)
                    ->orWhere('penguji1', $dosenId)
                    ->orWhere('penguji2', $dosenId)
                    ->orWhere('penguji3', $dosenId)
                    ->get();

        return view('koorHome', compact('sempros'));
    }

    public function adminHome(): View
    {
        $sempros = Sempro::all();
        return view('adminHome', compact('sempros'));
    }

    public function rekapHome(): View
    {
        // Ambil data mahasiswa yang sudah sempro beserta status penilaian dari tabel nilai_sempro
        $mahasiswaSempro = Sempro::leftJoin('nilai_sempro', 'sempros.id', '=', 'nilai_sempro.id_sempro')
        ->select('sempros.id', 'sempros.nama', 'sempros.jurusan','sempros.seminar','sempros.dospem2','sempros.status_nilai')
        ->selectRaw('COUNT(nilai_sempro.id) as jumlah_nilai')
        ->groupBy('sempros.id', 'sempros.nama', 'sempros.jurusan', 'sempros.seminar', 'sempros.dospem2', 'sempros.status_nilai')
        ->get();

        return view('koor.rekapitulasi', compact('mahasiswaSempro'));
    }

    public function profile()
    {
        $user = Auth::user();
        $signature = $user->signature;

        return view('profile.show', compact('user', 'signature'));
    }

    public function saveSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('signature')) {
            $image = $request->file('signature');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            $signature = new Signature();
            $signature->signature_path = $imageName;

            $user->signature()->save($signature);
        }

        return redirect()->route('profile')->with('success', 'Tanda tangan berhasil ditambahkan.');
    }
    
}
