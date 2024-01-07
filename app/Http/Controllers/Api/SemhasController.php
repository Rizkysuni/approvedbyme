<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semhas;

use App\Models\Sempro;
use App\Models\User;
use App\Models\NilaiSemhas;
use App\Models\Signature;

use Aspose\Words\WordsApi;
use Aspose\Words\Model\Requests\ConvertDocumentRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Env;

//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

use Dompdf\Dompdf;

class SemhasController extends Controller
{
    // Method untuk menampilkan data seminar hasil
    public function index()
    {
        $seminarHasil = Semhas::all(); // Ambil semua data dari tabel "semhas"
        return response()->json($seminarHasil, 200); // Kirim data sebagai response JSON
    }

    /**
     * create view
     *
     * @param  mixed $request
     * @return void
     */
   public function create()
   {
        // Get the currently logged-in user (mahasiswa)
        $user = Auth::user();
        $userId = auth()->user()->id;

        // Cek apakah pengguna sudah memiliki rekaman Sempro
        $seminarHasilRegistered = Sempro::where('id_mahasiswa', $userId)
            ->where('seminar', 'Seminar Hasil')
            ->exists();

        $SemproRegistered = Sempro::where('id_mahasiswa', $userId)
            ->where('seminar', 'Seminar Proposal')
            ->exists();

        if ($seminarHasilRegistered) {
                return redirect()->route('home')->with('error', 'Anda Sudah Seminar Hasil.');
        } elseif (!$SemproRegistered){
                return redirect()->route('home')->with('error', 'Anda Belum Seminar Proposal.');
        } else {

        $sempros = Sempro::where('id_mahasiswa', $user->id)
            ->where('seminar', 'seminar proposal')
            ->first();

       return view('mahasiswa.createSemhas', compact('sempros'));}
   }

  /**
    * store
    *
    * @param  mixed $request
    * @return void
    */
   public function store(Request $request)
   {
       
       //define validation rules
       $validator = Validator::make($request->all(), [
           'id_mahasiswa'  => 'required',
           'nama'          => 'required',
           'nim'           => 'required',
           'judul'         => 'required',
           'jurusan'       => 'required',
           'ruangan'       => 'required',
           'tglSempro'     => 'required',
           'dospem1'       => 'required',
           'dospem2'       => 'required',
           'penguji1'      => 'required',
           'penguji2'      => 'required',
           'penguji3'      => 'required',
           'jam'           => 'required'
       ]);

       //check if validation fails
       if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
       }


       //create sempro
       $sempro = Sempro::create([
           'id_mahasiswa' => $request->id_mahasiswa,
           'nama'      => $request->nama,
           'nim'       => $request->nim,
           'judul'     => $request->judul,
           'jurusan'   => $request->jurusan,
           'ruangan'   => $request->ruangan,
           'tglSempro' => $request->tglSempro,
           'dospem1'   => $request->dospem1,
           'dospem2'   => $request->dospem2,
           'penguji1'  => $request->penguji1,
           'penguji2'  => $request->penguji2,
           'penguji3'  => $request->penguji3,
           'seminar'   => 'Seminar Hasil', // Set default value for 'seminar'
           'jam'       => $request->jam
       ]);

       // Redirect user back to home
       return redirect()->route('home');
   }

   public function beriNilai($id)
    {
        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }

        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro
        return view('/dosen/beriNilaiSemhas', compact('sempro'));
    }

    public function simpanNilai(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_sempro' => 'required',
            'id_dosen'  => 'required',
            'nilai_1'   => 'required',
            'nilai_2'   => 'required',
            'nilai_3'   => 'required',
            'nilai_4'   => 'required',
            'nilai_5'   => 'required',
            'nilai_6'   => 'required',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan nilai ke dalam tabel nilaiSempro
        $nilaiSempro = NilaiSemhas::create([
            'id_sempro' => $request->id_sempro,
            'id_dosen'  => $request->id_dosen,
            'nilai_1'   => $request->nilai_1,
            'nilai_2'   => $request->nilai_2,            
            'nilai_3'   => $request->nilai_3,
            'nilai_4'   => $request->nilai_4,            
            'nilai_5'   => $request->nilai_5,
            'nilai_6'   => $request->nilai_6,
        ]);

        // Redirect atau tampilkan notifikasi sesuai kebutuhan
        if (auth()->user()->role == 'dosen') {
            return redirect()->route('dosen.home')->with('success', 'Nilai berhasil disimpan');
        } elseif (auth()->user()->role == 'koordinator') {
            return redirect()->route('koor.home')->with('success', 'Nilai berhasil disimpan');
        }
    }

    public function pen09Home()
    {
        $dosenId = auth()->user()->id;
        $semhas = Sempro::where(function($query) use ($dosenId) {
            $query->where('dospem2', $dosenId)
                  ->orWhere('dospem1', $dosenId);
        })
        ->where('status_nilai', 'belum dinilai')
        ->where('seminar', 'Seminar Hasil')
        ->get();
        

        return view('dosen.pen09Home', compact('semhas'));
    }

    public function pen09($id)
    {
        // Ambil data sempro berdasarkan ID
        $sempro= Sempro::find($id);
    
        // Ambil ID dosen dari kolom dospem1 pada tabel sempros
        $dospem1Id = $sempro->dospem1;
        $dospem2Id = $sempro->dospem2;
        $penguji1Id = $sempro->penguji1;
        $penguji2Id = $sempro->penguji2;
        $penguji3Id = $sempro->penguji3;

        // Ambil nama dosen berdasarkan ID dari tabel users
        $dospem1 = User::find($dospem1Id);
        $dospem2 = User::find($dospem2Id);
        $penguji1 = User::find($penguji1Id);
        $penguji2 = User::find($penguji2Id);
        $penguji3 = User::find($penguji3Id);

        // Sekarang Anda memiliki nama-nama dosen
        $namaDospem1 = $dospem1 ? $dospem1->name : 'Dosen 1 Tidak Ditemukan';
        $namaDospem2 = $dospem2 ? $dospem2->name : 'Dosen 2 Tidak Ditemukan';
        $namaPenguji1 = $penguji1 ? $penguji1->name : 'Penguji 1 Tidak Ditemukan';
        $namaPenguji2 = $penguji2 ? $penguji2->name : 'Penguji 2 Tidak Ditemukan';
        $namaPenguji3 = $penguji3 ? $penguji3->name : 'Penguji 3 Tidak Ditemukan';

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        $nilaiDosen = NilaiSemhas::where('id_sempro', $id)->get();

        // nilai dospem 1
        $nilaiDospem1 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $dospem1Id)
        ->first();
        if ($nilaiDospem1) {

        $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.25;
        $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.1;
        $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.2;
        $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
        $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.1;
        $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.25;
        $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1 + $nilai6dospem1;
        } else {
            // Data tidak ditemukan
            $nilai1dospem1 = null;
            $nilai2dospem1 = null;
            $nilai3dospem1 = null;
            $nilai4dospem1 = null;
            $nilai5dospem1 = null;
            $nilai6dospem1 = null;

            $tot1 = null;
        }

        // nilai dospem 2
        $nilaiDospem2 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $dospem2Id)
        ->first();
        if ($nilaiDospem2) {

        $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.25;
        $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.1;
        $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.2;
        $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
        $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.1;
        $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.25;
        $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2 + $nilai6dospem2;
        } else {
            // Data tidak ditemukan
            $nilai1dospem2 = null;
            $nilai2dospem2 = null;
            $nilai3dospem2 = null;
            $nilai4dospem2 = null;
            $nilai5dospem2 = null;
            $nilai6dospem2 = null;

            $tot2 = null;
        }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji1Id)
        ->first();
        if ($nilaiPenguji1) {

        $nilai1Penguji1 = $nilaiPenguji1->nilai_1 * 0.25;
        $nilai2Penguji1 = $nilaiPenguji1->nilai_2 * 0.1;
        $nilai3Penguji1 = $nilaiPenguji1->nilai_3 * 0.2;
        $nilai4Penguji1 = $nilaiPenguji1->nilai_4 * 0.1;
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.1;
        $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.25;
        $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1 + $nilai6Penguji1;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji1 = null;
            $nilai2Penguji1 = null;
            $nilai3Penguji1 = null;
            $nilai4Penguji1 = null;
            $nilai5Penguji1 = null;
            $nilai6Penguji1 = null;

            $tot3 = null;
        }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji2Id)
        ->first();
        if ($nilaiPenguji2) {

        $nilai1Penguji2 = $nilaiPenguji2->nilai_1 * 0.25;
        $nilai2Penguji2 = $nilaiPenguji2->nilai_2 * 0.1;
        $nilai3Penguji2 = $nilaiPenguji2->nilai_3 * 0.2;
        $nilai4Penguji2 = $nilaiPenguji2->nilai_4 * 0.1;
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.1;
        $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.25;
        $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2+ $nilai4Penguji2 + $nilai5Penguji2 + $nilai6Penguji2;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji2 = null;
            $nilai2Penguji2 = null;
            $nilai3Penguji2 = null;
            $nilai4Penguji2 = null;
            $nilai5Penguji2 = null;
            $nilai6Penguji2 = null;

            $tot4 = null;
        }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji3Id)
        ->first();
        if ($nilaiPenguji3) {

        $nilai1Penguji3 = $nilaiPenguji3->nilai_1 * 0.25;
        $nilai2Penguji3 = $nilaiPenguji3->nilai_2 * 0.1;
        $nilai3Penguji3 = $nilaiPenguji3->nilai_3 * 0.2;
        $nilai4Penguji3 = $nilaiPenguji3->nilai_4 * 0.1;
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.1;
        $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.25;
        $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3+ $nilai4Penguji3 + $nilai5Penguji3 + $nilai6Penguji3;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji3 = null;
            $nilai2Penguji3 = null;
            $nilai3Penguji3 = null;
            $nilai4Penguji3 = null;
            $nilai5Penguji3 = null;
            $nilai6Penguji3 = null;

            $tot5 = null;
        }

        $totalNilaiKeseluruhan = $tot5 + $tot4 + $tot3 + $tot2 + $tot1;
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5; 

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }


        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro
        return view('/dosen/pen09', compact('sempro','nilaiDosen','totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan',
        'tot1','tot2','tot3','tot4','tot5','namaDospem1','namaDospem2','namaPenguji1','namaPenguji2','namaPenguji3'));
    }

    public function sendDataToCoordinator()
    {
        // Ambil data sempro berdasarkan ID dosen yang sedang login
        $dosenId = auth()->user()->id;
        $semhasList = Sempro::where(function($query) use ($dosenId) {
            $query->where('dospem2', $dosenId)
                ->orWhere('dospem1', $dosenId);
        })
                            ->where('seminar', 'Seminar Hasil')
                            ->get();

        // Ubah nilai status_nilai menjadi "selesai dinilai" untuk setiap sempro
        foreach ($semhasList as $semhas) {
            $semhas->status_nilai = 'selesai dinilai';
            $semhas->save();
        }

        // Redirect kembali ke halaman pen05Home setelah nilai berhasil diubah
        return redirect()->route('dosen.pen09Home')
            ->with('success', 'Data berhasil dikirim ke koordinator!');
    }


    public function rekapNilai($id)
    {
        
            // Ambil data sempro berdasarkan ID
            $sempro = Sempro::find($id);
    
            // Ambil ID dosen dari kolom dospem1 pada tabel sempros
            $dospem1Id = $sempro->dospem1;
            $dospem2Id = $sempro->dospem2;
            $penguji1Id = $sempro->penguji1;
            $penguji2Id = $sempro->penguji2;
            $penguji3Id = $sempro->penguji3;

            // Ambil nama dosen berdasarkan ID dari tabel users
            $dospem1 = User::find($dospem1Id);
            $dospem2 = User::find($dospem2Id);
            $penguji1 = User::find($penguji1Id);
            $penguji2 = User::find($penguji2Id);
            $penguji3 = User::find($penguji3Id);

            // Sekarang Anda memiliki nama-nama dosen
            $namaDospem1 = $dospem1 ? $dospem1->name : 'Dosen 1 Tidak Ditemukan';
            $namaDospem2 = $dospem2 ? $dospem2->name : 'Dosen 2 Tidak Ditemukan';
            $namaPenguji1 = $penguji1 ? $penguji1->name : 'Penguji 1 Tidak Ditemukan';
            $namaPenguji2 = $penguji2 ? $penguji2->name : 'Penguji 2 Tidak Ditemukan';
            $namaPenguji3 = $penguji3 ? $penguji3->name : 'Penguji 3 Tidak Ditemukan';
    
            // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
            $nilaiDosen = NilaiSemhas::where('id_sempro', $id)->get();

            // nilai dospem 1
            $nilaiDospem1 = NilaiSemhas::where('id_sempro', $id)
            ->where('id_dosen', $dospem1Id)
            ->first();
            if ($nilaiDospem1) {

            $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.25;
            $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.1;
            $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.2;
            $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
            $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.1;
            $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.25;
            $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1 + $nilai6dospem1;
            } else {
                // Data tidak ditemukan
                $nilai1dospem1 = null;
                $nilai2dospem1 = null;
                $nilai3dospem1 = null;
                $nilai4dospem1 = null;
                $nilai5dospem1 = null;
                $nilai6dospem1 = null;

                $tot1 = null;
            }

            // nilai dospem 2
            $nilaiDospem2 = NilaiSemhas::where('id_sempro', $id)
            ->where('id_dosen', $dospem2Id)
            ->first();
            if ($nilaiDospem2) {

            $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.25;
            $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.1;
            $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.2;
            $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
            $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.1;
            $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.25;
            $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2 + $nilai6dospem2;
            } else {
                // Data tidak ditemukan
                $nilai1dospem2 = null;
                $nilai2dospem2 = null;
                $nilai3dospem2 = null;
                $nilai4dospem2 = null;
                $nilai5dospem2 = null;
                $nilai6dospem2 = null;

                $tot2 = null;
            }

            // nilai penguji 1
            $nilaiPenguji1 = NilaiSemhas::where('id_sempro', $id)
            ->where('id_dosen', $penguji1Id)
            ->first();
            if ($nilaiPenguji1) {

            $nilai1Penguji1 = $nilaiPenguji1->nilai_1 * 0.25;
            $nilai2Penguji1 = $nilaiPenguji1->nilai_2 * 0.1;
            $nilai3Penguji1 = $nilaiPenguji1->nilai_3 * 0.2;
            $nilai4Penguji1 = $nilaiPenguji1->nilai_4 * 0.1;
            $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.1;
            $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.25;
            $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1 + $nilai6Penguji1;
            } else {
                // Data tidak ditemukan
                $nilai1Penguji1 = null;
                $nilai2Penguji1 = null;
                $nilai3Penguji1 = null;
                $nilai4Penguji1 = null;
                $nilai5Penguji1 = null;
                $nilai6Penguji1 = null;

                $tot3 = null;
            }

            // nilai penguji 2
            $nilaiPenguji2 = NilaiSemhas::where('id_sempro', $id)
            ->where('id_dosen', $penguji2Id)
            ->first();
            if ($nilaiPenguji2) {

            $nilai1Penguji2 = $nilaiPenguji2->nilai_1 * 0.25;
            $nilai2Penguji2 = $nilaiPenguji2->nilai_2 * 0.1;
            $nilai3Penguji2 = $nilaiPenguji2->nilai_3 * 0.2;
            $nilai4Penguji2 = $nilaiPenguji2->nilai_4 * 0.1;
            $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.1;
            $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.25;
            $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2+ $nilai4Penguji2 + $nilai5Penguji2 + $nilai6Penguji2;
            } else {
                // Data tidak ditemukan
                $nilai1Penguji2 = null;
                $nilai2Penguji2 = null;
                $nilai3Penguji2 = null;
                $nilai4Penguji2 = null;
                $nilai5Penguji2 = null;
                $nilai6Penguji2 = null;

                $tot4 = null;
            }

            // nilai penguji 3
            $nilaiPenguji3 = NilaiSemhas::where('id_sempro', $id)
            ->where('id_dosen', $penguji3Id)
            ->first();
            if ($nilaiPenguji3) {

            $nilai1Penguji3 = $nilaiPenguji3->nilai_1 * 0.25;
            $nilai2Penguji3 = $nilaiPenguji3->nilai_2 * 0.1;
            $nilai3Penguji3 = $nilaiPenguji3->nilai_3 * 0.2;
            $nilai4Penguji3 = $nilaiPenguji3->nilai_4 * 0.1;
            $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.1;
            $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.25;
            $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3+ $nilai4Penguji3 + $nilai5Penguji3 + $nilai6Penguji3;
            } else {
                // Data tidak ditemukan
                $nilai1Penguji3 = null;
                $nilai2Penguji3 = null;
                $nilai3Penguji3 = null;
                $nilai4Penguji3 = null;
                $nilai5Penguji3 = null;
                $nilai6Penguji3 = null;

                $tot5 = null;
            }
    
            $totalNilaiKeseluruhan = $tot5 + $tot4 + $tot3 + $tot2 + $tot1;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5; 
    
            // Cek apakah sempro ditemukan
            if (!$sempro) {
                abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
            }
            // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
            return view('/koor/rekapNilaiSemhas', compact('sempro', 'nilaiDosen', 'totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan',
            'tot1','tot2','tot3','tot4','tot5','namaDospem1','namaDospem2','namaPenguji1','namaPenguji2','namaPenguji3'));
                
        
    }

    public function exportPDF($id)
    {
        // Load file template .docx
        $templateFilePath = storage_path('app/pen09/pen09.docx');
        $templateProcessor = new TemplateProcessor($templateFilePath);

        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);
        // Ambil ID dosen dari kolom dospem1 pada tabel sempros
        $dospem1Id = $sempro->dospem1;
        $dospem2Id = $sempro->dospem2;
        $penguji1Id = $sempro->penguji1;
        $penguji2Id = $sempro->penguji2;
        $penguji3Id = $sempro->penguji3;

        // Ambil tanda tangan dosen pembimbing 1
        $ttd1 = Signature::where('user_id', $dospem1Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd1Path = $ttd1 ? public_path('images/' . $ttd1->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd1", [
            'path' => $ttd1Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil tanda tangan dosen pembimbing 2
        $ttd2 = Signature::where('user_id', $dospem2Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd2Path = $ttd2 ? public_path('images/' . $ttd2->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd2", [
            'path' => $ttd2Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);
        // Add the image to the template
        $templateProcessor->setImageValue("ttdketua", [
            'path' => $ttd2Path,
            'width' => 160, // Set the width of the image in the document
            'height' => 80, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil tanda tangan dosen penguji 1
        $ttd3 = Signature::where('user_id', $penguji1Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd3Path = $ttd3 ? public_path('images/' . $ttd3->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd3", [
            'path' => $ttd3Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil tanda tangan dosen penguji 2
        $ttd4 = Signature::where('user_id', $penguji2Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd4Path = $ttd4 ? public_path('images/' . $ttd4->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd4", [
            'path' => $ttd4Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil tanda tangan dosen penguji 3
        $ttd5 = Signature::where('user_id', $penguji3Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd5Path = $ttd5 ? public_path('images/' . $ttd5->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd5", [
            'path' => $ttd5Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil nama dosen berdasarkan ID dari tabel users
        $dp1 = User::find($dospem1Id);
        $dp2 = User::find($dospem2Id);
        $pen1 = User::find($penguji1Id);
        $pen2 = User::find($penguji2Id);
        $pen3 = User::find($penguji3Id);

        // Sekarang Anda memiliki nama-nama dosen
        $namaDp1 = $dp1 ? $dp1->name : 'Dosen 1 Tidak Ditemukan';
        $namaDp2 = $dp2 ? $dp2->name : 'Dosen 2 Tidak Ditemukan';
        $namaPen1 = $pen1 ? $pen1->name : 'Penguji 1 Tidak Ditemukan';
        $namaPen2 = $pen2 ? $pen2->name : 'Penguji 2 Tidak Ditemukan';
        $namaPen3 = $pen3 ? $pen3->name : 'Penguji 3 Tidak Ditemukan';

        // Sekarang Anda memiliki nim dosen
        $nimDp1 = $dp1 ? $dp1->nim : 'Dosen 1 Tidak Ditemukan';
        $nimDp2 = $dp2 ? $dp2->nim : 'Dosen 2 Tidak Ditemukan';
        $nimPen1 = $pen1 ? $pen1->nim : 'Penguji 1 Tidak Ditemukan';
        $nimPen2 = $pen2 ? $pen2->nim : 'Penguji 2 Tidak Ditemukan';
        $nimPen3 = $pen3 ? $pen3->nim : 'Penguji 3 Tidak Ditemukan';

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        //$nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();
        $nilaiDosen = NilaiSemhas::where('id_sempro', $id)
            ->with('dosen.signature') // Mengambil data tanda tangan dari tabel signatures yang berhubungan dengan tabel users
            ->get();


        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        $nilaiDosen = NilaiSemhas::where('id_sempro', $id)->get();

        // nilai dospem 1
        $nilaiDospem1 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $dospem1Id)
        ->first();
        if ($nilaiDospem1) {

        $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.25;
        $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.1;
        $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.2;
        $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
        $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.1;
        $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.25;
        $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1 + $nilai6dospem1;
        // nilai dospem 1
        $templateProcessor->setValue("tot1", $tot1);
        } else {
            // Data tidak ditemukan
            $nilai1dospem1 = null;
            $nilai2dospem1 = null;
            $nilai3dospem1 = null;
            $nilai4dospem1 = null;
            $nilai5dospem1 = null;
            $nilai6dospem1 = null;

            $tot1 = null;
        }

        // nilai dospem 2
        $nilaiDospem2 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $dospem2Id)
        ->first();
        if ($nilaiDospem2) {

        $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.25;
        $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.1;
        $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.2;
        $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
        $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.1;
        $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.25;
        $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2 + $nilai6dospem2;
        // nilai dospem 1
        $templateProcessor->setValue("tot2", $tot2);
        } else {
            // Data tidak ditemukan
            $nilai1dospem2 = null;
            $nilai2dospem2 = null;
            $nilai3dospem2 = null;
            $nilai4dospem2 = null;
            $nilai5dospem2 = null;
            $nilai6dospem2 = null;

            $tot2 = null;
        }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji1Id)
        ->first();
        if ($nilaiPenguji1) {

        $nilai1Penguji1 = $nilaiPenguji1->nilai_1 * 0.25;
        $nilai2Penguji1 = $nilaiPenguji1->nilai_2 * 0.1;
        $nilai3Penguji1 = $nilaiPenguji1->nilai_3 * 0.2;
        $nilai4Penguji1 = $nilaiPenguji1->nilai_4 * 0.1;
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.1;
        $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.25;
        $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1 + $nilai6Penguji1;
        // nilai dospem 1
        $templateProcessor->setValue("tot3", $tot3);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji1 = null;
            $nilai2Penguji1 = null;
            $nilai3Penguji1 = null;
            $nilai4Penguji1 = null;
            $nilai5Penguji1 = null;
            $nilai6Penguji1 = null;

            $tot3 = null;
        }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji2Id)
        ->first();
        if ($nilaiPenguji2) {

        $nilai1Penguji2 = $nilaiPenguji2->nilai_1 * 0.25;
        $nilai2Penguji2 = $nilaiPenguji2->nilai_2 * 0.1;
        $nilai3Penguji2 = $nilaiPenguji2->nilai_3 * 0.2;
        $nilai4Penguji2 = $nilaiPenguji2->nilai_4 * 0.1;
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.1;
        $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.25;
        $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2+ $nilai4Penguji2 + $nilai5Penguji2 + $nilai6Penguji2;
        // nilai dospem 1
        $templateProcessor->setValue("tot4", $tot4);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji2 = null;
            $nilai2Penguji2 = null;
            $nilai3Penguji2 = null;
            $nilai4Penguji2 = null;
            $nilai5Penguji2 = null;
            $nilai6Penguji2 = null;

            $tot4 = null;
        }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSemhas::where('id_sempro', $id)
        ->where('id_dosen', $penguji3Id)
        ->first();
        if ($nilaiPenguji3) {

        $nilai1Penguji3 = $nilaiPenguji3->nilai_1 * 0.25;
        $nilai2Penguji3 = $nilaiPenguji3->nilai_2 * 0.1;
        $nilai3Penguji3 = $nilaiPenguji3->nilai_3 * 0.2;
        $nilai4Penguji3 = $nilaiPenguji3->nilai_4 * 0.1;
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.1;
        $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.25;
        $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3+ $nilai4Penguji3 + $nilai5Penguji3 + $nilai6Penguji3;
        // nilai dospem 1
        $templateProcessor->setValue("tot5", $tot5);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji3 = null;
            $nilai2Penguji3 = null;
            $nilai3Penguji3 = null;
            $nilai4Penguji3 = null;
            $nilai5Penguji3 = null;
            $nilai6Penguji3 = null;

            $tot5 = null;
        }

        $totalNilaiKeseluruhan = $tot5 + $tot4 + $tot3 + $tot2 + $tot1;
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;

        // nilai dospem 1
        $templateProcessor->setValue("total", $totalNilaiKeseluruhan);
        $templateProcessor->setValue("rerata", $totalRerataNilaiKeseluruhan);

        
        // Hitung total rerata nilai keseluruhan
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / count($nilaiDosen);

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }

        // Ubah format tanggal dari database ke format yang diinginkan
        $tanggalSeminar = \Carbon\Carbon::createFromFormat('Y-m-d', $sempro->tglSempro)
                        ->locale('id') // Tetapkan locale ke bahasa Indonesia
                        ->isoFormat('dddd [tanggal] D [bulan] MMMM [tahun] YYYY');
        
        $tanggalSeminar1 = \Carbon\Carbon::createFromFormat('Y-m-d', $sempro->tglSempro)
                        ->locale('id') // Tetapkan locale ke bahasa Indonesia
                        ->isoFormat('D MMMM  YYYY');
                        

        // Isi data ke dalam template .docx
        $templateProcessor->setValue('nama', $sempro->nama);
        $templateProcessor->setValue('nim', $sempro->nim);
        $templateProcessor->setValue('judul', $sempro->judul);
        $templateProcessor->setValue('jurusan', $sempro->jurusan);
        $templateProcessor->setValue('ruangan', $sempro->ruangan);
        $templateProcessor->setValue('tanggal_seminar', $tanggalSeminar);
        $templateProcessor->setValue('tanggal_seminar1', $tanggalSeminar1);

        //panggil nama dan nim dosen
        $templateProcessor->setValue('namaDp1', $namaDp1);
        $templateProcessor->setValue('nimDp1', $nimDp1);
        $templateProcessor->setValue('namaDp2', $namaDp2);
        $templateProcessor->setValue('nimDp2', $nimDp2);
        $templateProcessor->setValue('namaPen1', $namaPen1);
        $templateProcessor->setValue('nimPen1', $nimPen1);
        $templateProcessor->setValue('namaPen2', $namaPen2);
        $templateProcessor->setValue('nimPen2', $nimPen2);
        $templateProcessor->setValue('namaPen3', $namaPen3);
        $templateProcessor->setValue('nimPen3', $nimPen3);
        

         // Add the condition for "LULUS" or "TIDAK LULUS" based on the totalRerataNilaiKeseluruhan
    $statusLulus = $totalRerataNilaiKeseluruhan > 68 ? 'LULUS' : 'TIDAK LULUS';

    // Modify the template based on status
    if ($statusLulus === 'LULUS') {
        $templateProcessor->setValue('kelulusan1', $statusLulus);
        $templateProcessor->setValue('kelulusan2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'TIDAK LULUS' . '</w:t></w:r>');
    } else {
        $templateProcessor->setValue('kelulusan1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'LULUS' . '</w:t></w:r>');
        $templateProcessor->setValue('kelulusan2', $statusLulus);
    }


    if ($totalRerataNilaiKeseluruhan >= 87) {
        $templateProcessor->setValue('cat1', 'A');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
    } elseif ($totalRerataNilaiKeseluruhan >= 78 && $totalRerataNilaiKeseluruhan < 87) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', 'AB');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
    } elseif ($totalRerataNilaiKeseluruhan >= 69 && $totalRerataNilaiKeseluruhan < 78) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', 'B');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
    } elseif ($totalRerataNilaiKeseluruhan >= 60 && $totalRerataNilaiKeseluruhan < 69) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', 'BC');
    }
        

        // Simpan file .docx yang sudah diisi data
        $outputFileName = "{$sempro->nama}_pen09.docx"; // Nama file sesuai dengan nama mahasiswa
        $outputFilePath = storage_path('app/pen09/') . $outputFileName;
        $templateProcessor->saveAs($outputFilePath);

        // Download the generated Docx file
        //return response()->download($outputFilePath, $outputFileName);
        // Redirect pengguna ke halaman untuk melihat file Word
        //return redirect()->route('koor.home');

        // Convert the Word document to PDF using Dompdf
        // Convert the Word document to HTML using PhpWord
        // Load the DOCX file

        // Inisialisasi variabel WordsApi dengan clientId dan clientSecret Anda
        $clientId = env('CLIENT_ID');
        $clientSecret = env('CLIENT_SECRET');
        $wordsApi = new WordsApi($clientId, $clientSecret);
          
       // Convert the Word document to PDF using Aspose.Words Cloud SDK
        // Convert the Word document to PDF using Aspose.Words Cloud SDK
        $format = 'pdf';
        $file = $outputFilePath;

        $request = new ConvertDocumentRequest($file, $format, null);
        $result = $wordsApi->convertDocument($request);

        // Get the contents of the PDF file from the SplFileObject
        $pdfContents = '';
        while (!$result->eof()) {
            $pdfContents .= $result->fread(4096); // Read 4096 bytes at a time
        }

        // Simpan file PDF yang dihasilkan
        $pdfOutputFileName = "{$sempro->nama}_pen09.pdf"; // Nama file sesuai dengan nama mahasiswa
        $pdfOutputFilePath = storage_path('app/pen09/') . $pdfOutputFileName;
        file_put_contents($pdfOutputFilePath, $pdfContents);

        // Hapus file .docx setelah diubah ke PDF
        unlink($outputFilePath);

        // Redirect pengguna ke halaman untuk melihat file PDF
        //return redirect()->back()->with('success', 'File PDF berhasil di-generate');


        // atau Download the generated PDF file
        return response()->download($pdfOutputFilePath, $pdfOutputFileName);  

      
    }

    public static function calculateAverageRerataNilaiKeseluruhan($idMahasiswa)
    {
        // Calculate the average value of totalRerataNilaiKeseluruhan
        $sempros = Sempro::where('id_mahasiswa', $idMahasiswa)
                            ->where('seminar','Seminar Hasil')
                            ->get();
                            
        $semproIds = $sempros->pluck('id');
        $nilaiDosen = NilaiSemhas::where('id_sempro', $semproIds)->get();
        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;



        // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $nilai) {
            // Apply the weights to each nilai_x
            $nilai_1_weighted = $nilai->nilai_1 * 0.25;
            $nilai_2_weighted = $nilai->nilai_2 * 0.1;
            $nilai_3_weighted = $nilai->nilai_3 * 0.2;
            $nilai_4_weighted = $nilai->nilai_4 * 0.1;
            $nilai_5_weighted = $nilai->nilai_5 * 0.1;
            $nilai_6_weighted = $nilai->nilai_6 * 0.25;

            // Calculate the total weighted nilai
            $totalNilai = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted + $nilai_5_weighted + $nilai_6_weighted;

            // Update the total_nilai property of the $nilai object
            $nilai->total_nilai = $totalNilai;

            // Tambahkan nilai pada total nilai keseluruhan
            $totalNilaiKeseluruhan += $nilai->total_nilai;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
        } 

        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
        return $totalRerataNilaiKeseluruhan;
    }
}
