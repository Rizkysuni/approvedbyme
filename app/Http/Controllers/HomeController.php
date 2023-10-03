<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sempro;
use App\Models\User;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Carbon\Carbon;


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
        $currentTime = now();
        // dd($currentTime);
        $sempros = Sempro::where('dospem1', $dosenId)
                    ->orWhere('dospem2', $dosenId)
                    ->orWhere('penguji1', $dosenId)
                    ->orWhere('penguji2', $dosenId)
                    ->orWhere('penguji3', $dosenId)
                    ->get();
        // Loop melalui $sempros dan tambahkan properti DateTime
        foreach ($sempros as $sempro) {
            $semproDateTimeStr = $sempro->tglSempro . ' ' . $sempro->jam;
            $sempro->semproDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $semproDateTimeStr); // Mengatur zona waktu yang sama dengan currentTime
        }
        return view('dosenHome', compact('sempros','currentTime'));
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function koorHome(): View
    {
        $dosenId = auth()->user()->id;
        $currentTime = now();
        $sempros = Sempro::where('dospem1', $dosenId)
                    ->orWhere('dospem2', $dosenId)
                    ->orWhere('penguji1', $dosenId)
                    ->orWhere('penguji2', $dosenId)
                    ->orWhere('penguji3', $dosenId)
                    ->get();
        foreach ($sempros as $sempro) {
            $semproDateTimeStr = $sempro->tglSempro . ' ' . $sempro->jam;
            $sempro->semproDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $semproDateTimeStr); // Mengatur zona waktu yang sama dengan currentTime
        }
        return view('koorHome', compact('sempros','currentTime'));
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
        ->leftJoin('nilai_semhas', 'sempros.id', '=', 'nilai_semhas.id_sempro')
        ->leftJoin('nilai_sidang', 'sempros.id', '=', 'nilai_sidang.id_sempro')
        ->select('sempros.id', 'sempros.nama', 'sempros.jurusan', 'sempros.seminar', 'sempros.dospem2', 'sempros.status_nilai')
        ->selectRaw('
            COUNT(nilai_sempro.id) as jumlah_nilai_sempro,
            COUNT(nilai_semhas.id) as jumlah_nilai_semhas,
            COUNT(nilai_sidang.id) as jumlah_nilai_sidang
        ')
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

    public function editFoto(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            Auth::user()->update([
                'gambar' => $imageName,
            ]);

            return redirect()->route('profile')->with('success', 'Foto Profile berhasil diubah!');
        }

        return redirect()->route('profile')->with('error', 'Gagal mengubah foto profil!');
    }

    public function editTtd(Request $request)
    {
        $request->validate([
            'ttd' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('ttd')) {
            $ttd = $request->file('ttd');
            $ttdName = time() . '.' . $ttd->getClientOriginalExtension();
            $ttd->move(public_path('images'), $ttdName);

            // Ambil objek Signature yang sesuai dengan pengguna yang sedang masuk
            $signature = Signature::where('user_id', auth()->user()->id)->first();

            if ($signature) {
                $signature->signature_path = $ttdName; // Sesuaikan dengan direktori penyimpanan Anda
                $signature->save();

                return redirect()->route('profile')->with('success', 'Tanda tangan berhasil diubah!');
            } else {
                return redirect()->route('profile')->with('error', 'Tanda tangan tidak ditemukan!');
            }
        }

        return redirect()->route('profile')->with('error', 'Gagal mengubah tanda tangan!');
    }


    public function history() {
        // Mengambil data Sempro yang telah selesai dinilai
        $sempros = Sempro::where('status_nilai', 'selesai dinilai')->get();

        return view('history', compact('sempros'));
    }

    public function dosenHistory() {
        // Mengambil data Sempro yang telah selesai dinilai
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem1', $dosenId)
            ->orWhere('dospem2', $dosenId)
            ->orWhere('penguji1', $dosenId)
            ->orWhere('penguji2', $dosenId)
            ->orWhere('penguji3', $dosenId)
            ->get();

        return view('dosenHistory', compact('sempros'));
    }

    public function adminHistory() {
        $sempros = Sempro::where('status_nilai', 'selesai dinilai')->get();

        return view('adminHistory', compact('sempros'));
    }

    public function detail($id) {
        $dospem1 = Sempro::where('sempros.id', $id)
            ->join('users', 'sempros.dospem1', '=', 'users.id')
            ->select('sempros.*', 'users.name as namaDosen', 'users.nim as nip','users.jabatan as jabatan')
            ->first();

        $dospem2 = Sempro::where('sempros.id', $id)
            ->join('users', 'sempros.dospem2', '=', 'users.id')
            ->select('sempros.*', 'users.name as namaDosen', 'users.nim as nip','users.jabatan as jabatan')
            ->first();

        $penguji1 = Sempro::where('sempros.id', $id)
            ->join('users', 'sempros.penguji1', '=', 'users.id')
            ->select('sempros.*', 'users.name as namaDosen', 'users.nim as nip','users.jabatan as jabatan')
            ->first();

        $penguji2 = Sempro::where('sempros.id', $id)
            ->join('users', 'sempros.penguji2', '=', 'users.id')
            ->select('sempros.*', 'users.name as namaDosen', 'users.nim as nip','users.jabatan as jabatan')
            ->first();

        $penguji3 = Sempro::where('sempros.id', $id)
            ->join('users', 'sempros.penguji3', '=', 'users.id')
            ->select('sempros.*', 'users.name as namaDosen', 'users.nim as nip','users.jabatan as jabatan')
            ->first();

        return view('mahasiswa.detail', compact('dospem1','dospem2','penguji1','penguji2','penguji3'));
    }
    
}
