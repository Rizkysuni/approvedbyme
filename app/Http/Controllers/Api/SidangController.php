<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sidang;

use App\Models\Sempro;
use App\Models\User;
use App\Models\NilaiSidang;
use App\Models\Signature;

use Illuminate\Support\Facades\Auth;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

use Aspose\Words\WordsApi;
use Aspose\Words\Model\Requests\ConvertDocumentRequest;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

use App\Http\Controllers\Api\SemhasController;

class SidangController extends Controller
{
    public function index()
    {
        $sidangs = Sidang::all();

        return response()->json($sidangs);
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

        $SidangRegistered = Sempro::where('id_mahasiswa', $userId)
            ->where('seminar', 'Sidang Akhir')
            ->exists();

        if (!$seminarHasilRegistered) {
                return redirect()->route('home')->with('error', 'Anda Belum Seminar Hasil.');
        } elseif ($SidangRegistered){
                return redirect()->route('home')->with('error', 'Anda Sudah Sidang Akhir.');
        } else {

        $sempros = Sempro::where('id_mahasiswa', $user->id)
            ->where('seminar', 'Seminar Hasil')
            ->first();

       return view('mahasiswa.createSidang', compact('sempros'));}
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
           'seminar'   => 'Sidang Akhir', // Set default value for 'seminar'
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
        return view('/dosen/beriNilaiSidang', compact('sempro'));
    }

    public function simpanNilai(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_sempro' => 'required',
            'id_dosen'  => 'required',
            'nilai_1'   => '',
            'nilai_2'   => '',
            'nilai_3'   => '',
            'nilai_4'   => '',
            'nilai_5'   => 'required',
            'nilai_6'   => 'required',
            'nilai_7'   => 'required',
            'nilai_8'   => 'required',
            'nilai_9'   => 'required',
            // Validasi komponen penilaian lainnya
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan nilai ke dalam tabel nilaiSempro
        $nilaiSempro = NilaiSidang::create([
            'id_sempro' => $request->id_sempro,
            'id_dosen'  => $request->id_dosen,
            'nilai_1'   => $request->nilai_1,
            'nilai_2'   => $request->nilai_2,            
            'nilai_3'   => $request->nilai_3,
            'nilai_4'   => $request->nilai_4,            
            'nilai_5'   => $request->nilai_5,
            'nilai_6'   => $request->nilai_6,
            'nilai_7'   => $request->nilai_7,
            'nilai_8'   => $request->nilai_8,
            'nilai_9'   => $request->nilai_9,
            // Simpan komponen penilaian lainnya
        ]);

        // Redirect atau tampilkan notifikasi sesuai kebutuhan
        if (auth()->user()->role == 'dosen') {
            return redirect()->route('dosen.home')->with('success', 'Nilai berhasil disimpan');
        } elseif (auth()->user()->role == 'koordinator') {
            return redirect()->route('koor.home')->with('success', 'Nilai berhasil disimpan');
        }
    }

    public function pen14Home()
    {
        $dosenId = auth()->user()->id;
        $sidang = Sempro::where('dospem2', $dosenId)
                        // ->Where('status_nilai', 'belum dinilai')
                        ->Where('seminar', 'Sidang Akhir')
                    ->get();

        return view('dosen.pen14Home', compact('sidang'));
    }

    public function pen14($id)
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
            $nilaiDosen = NilaiSidang::where('id_sempro', $id)->get();

            // nilai dospem 1
            $nilaiDospem1 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $dospem1Id)
            ->first();
            if ($nilaiDospem1) {
            $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.1;
            $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.05;
            $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.05;
            $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
            $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.2;
            $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.05;
            $nilai7dospem1 = $nilaiDospem1->nilai_7 * 0.05;
            $nilai8dospem1 = $nilaiDospem1->nilai_8 * 0.3;
            $nilai9dospem1 = $nilaiDospem1->nilai_9 * 0.1;
            $komp1dp1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1;
            $komp2dp1 = $nilai5dospem1 + $nilai6dospem1 + $nilai7dospem1;
            $komp3dp1 = $nilai8dospem1 + $nilai9dospem1;
            } else {
                    // Data tidak ditemukan
                    $nilai1dospem1 = null;
                    $nilai2dospem1 = null;
                    $nilai3dospem1 = null;
                    $nilai4dospem1 = null;
                    $nilai5dospem1 = null;
                    $nilai6dospem1 = null;
                    $nilai7dospem1 = null;
                    $nilai8dospem1 = null;
                    $nilai9dospem1 = null;

                    $komp1dp1 = null;
                    $komp2dp1 = null;
                    $komp3dp1 =  null;
                }

            // nilai dospem 2
            $nilaiDospem2 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $dospem2Id)
            ->first();
            if ($nilaiDospem2) {
            $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.1;
            $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.05;
            $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.05;
            $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
            $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.2;
            $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.05;
            $nilai7dospem2 = $nilaiDospem2->nilai_7 * 0.05;
            $nilai8dospem2 = $nilaiDospem2->nilai_8 * 0.3;
            $nilai9dospem2 = $nilaiDospem2->nilai_9 * 0.1;
            $komp1dp2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2;
            $komp2dp2 = $nilai5dospem2 + $nilai6dospem2 + $nilai7dospem2;
            $komp3dp2 = $nilai8dospem2 + $nilai9dospem2;
            } else {
                    // Data tidak ditemukan
                    $nilai1dospem2 = null;
                    $nilai2dospem2 = null;
                    $nilai3dospem2 = null;
                    $nilai4dospem2 = null;
                    $nilai5dospem2 = null;
                    $nilai6dospem2 = null;
                    $nilai7dospem2 = null;
                    $nilai8dospem2 = null;
                    $nilai9dospem2 = null;

                    $komp1dp2 = null;
                    $komp2dp2 = null;
                    $komp3dp2 =  null;
                }

            // nilai penguji 1
            $nilaiPenguji1 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji1Id)
            ->first();
            if ($nilaiPenguji1) {
            $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.2;
            $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.05;
            $nilai7Penguji1 = $nilaiPenguji1->nilai_7 * 0.05;
            $nilai8Penguji1 = $nilaiPenguji1->nilai_8 * 0.3;
            $nilai9Penguji1 = $nilaiPenguji1->nilai_9 * 0.1;
            $komp2pg1 = $nilai5Penguji1 + $nilai6Penguji1 + $nilai7Penguji1;
            $komp3pg1 = $nilai8Penguji1 + $nilai9Penguji1;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji1 = null;
                    $nilai6Penguji1 = null;
                    $nilai7Penguji1 = null;
                    $nilai8Penguji1 = null;
                    $nilai9Penguji1 = null;
                    $komp2pg1 = null;
                    $komp3pg1 =  null;
                }

            // nilai penguji 2
            $nilaiPenguji2 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji2Id)
            ->first();
            if ($nilaiDospem2) {
            $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.2;
            $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.05;
            $nilai7Penguji2 = $nilaiPenguji2->nilai_7 * 0.05;
            $nilai8Penguji2 = $nilaiPenguji2->nilai_8 * 0.3;
            $nilai9Penguji2 = $nilaiPenguji2->nilai_9 * 0.1;
            $komp2pg2= $nilai5Penguji2 + $nilai6Penguji2 + $nilai7Penguji2;
            $komp3pg2 = $nilai8Penguji2 + $nilai9Penguji2;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji2 = null;
                    $nilai6Penguji2 = null;
                    $nilai7Penguji2 = null;
                    $nilai8Penguji2 = null;
                    $nilai9Penguji2 = null;
                    $komp2pg2 = null;
                    $komp3pg2 =  null;
                }

            // nilai penguji 3
            $nilaiPenguji3 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji3Id)
            ->first();
            if ($nilaiPenguji3) {
            $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.2;
            $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.05;
            $nilai7Penguji3 = $nilaiPenguji3->nilai_7 * 0.05;
            $nilai8Penguji3 = $nilaiPenguji3->nilai_8 * 0.3;
            $nilai9Penguji3 = $nilaiPenguji3->nilai_9 * 0.1;
            $komp2pg3= $nilai5Penguji3 + $nilai6Penguji3 + $nilai7Penguji3;
            $komp3pg3 = $nilai8Penguji3 + $nilai9Penguji3;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji3 = null;
                    $nilai6Penguji3 = null;
                    $nilai7Penguji3 = null;
                    $nilai8Penguji3 = null;
                    $nilai9Penguji3 = null;
                    $komp2pg3 = null;
                    $komp3pg3 =  null;
                }
    
            $totalNilaiKeseluruhan = 0;
            $totalRerataNilaiKeseluruhan = 0;
            $jumlahKomponen1 = $komp1dp2 + $komp1dp1;
            $jumlahKomponen2 = $komp2pg3+ $komp2pg2 + $komp2pg1 + $komp2dp1 + $komp2dp2;
            $jumlahKomponen3 = $komp3pg3+ $komp3pg2 + $komp3pg1 + $komp3dp1 + $komp3dp2;
    
    
            // Cek apakah sempro ditemukan
            if (!$sempro) {
                abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
            }
            // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
            return view('/dosen/pen14', compact('sempro', 'nilaiDosen', 'namaDospem2','namaDospem1','namaPenguji1','namaPenguji2',
            'namaPenguji3','jumlahKomponen1','jumlahKomponen2','jumlahKomponen3','komp1dp2','komp2dp2','komp3dp2','komp1dp1','komp2dp1','komp3dp1',
            'komp2pg1','komp3pg1','komp2pg2','komp3pg2','komp2pg3','komp3pg3',));
        }

    public function sendDataToCoordinator()
    {
        // Ambil data sempro berdasarkan ID dosen yang sedang login
        $dosenId = auth()->user()->id;
        $sidangList = Sempro::where('dospem2', $dosenId)
                            ->where('seminar', 'Sidang Akhir')
                            ->get();

        // Ubah nilai status_nilai menjadi "selesai dinilai" untuk setiap sempro
        foreach ($sidangList as $sidang) {
            $sidang->status_nilai = 'selesai dinilai';
            $sidang->save();
        }

        // Redirect kembali ke halaman pen05Home setelah nilai berhasil diubah
        return redirect()->route('dosen.pen14Home')
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
            $nilaiDosen = NilaiSidang::where('id_sempro', $id)->get();

            // nilai dospem 1
            $nilaiDospem1 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $dospem1Id)
            ->first();
            if ($nilaiDospem1) {
            $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.1;
            $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.05;
            $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.05;
            $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
            $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.2;
            $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.05;
            $nilai7dospem1 = $nilaiDospem1->nilai_7 * 0.05;
            $nilai8dospem1 = $nilaiDospem1->nilai_8 * 0.3;
            $nilai9dospem1 = $nilaiDospem1->nilai_9 * 0.1;
            $komp1dp1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1;
            $komp2dp1 = $nilai5dospem1 + $nilai6dospem1 + $nilai7dospem1;
            $komp3dp1 = $nilai8dospem1 + $nilai9dospem1;
            } else {
                    // Data tidak ditemukan
                    $nilai1dospem1 = null;
                    $nilai2dospem1 = null;
                    $nilai3dospem1 = null;
                    $nilai4dospem1 = null;
                    $nilai5dospem1 = null;
                    $nilai6dospem1 = null;
                    $nilai7dospem1 = null;
                    $nilai8dospem1 = null;
                    $nilai9dospem1 = null;

                    $komp1dp1 = null;
                    $komp2dp1 = null;
                    $komp3dp1 =  null;
                }

            // nilai dospem 2
            $nilaiDospem2 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $dospem2Id)
            ->first();
            if ($nilaiDospem2) {
            $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.1;
            $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.05;
            $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.05;
            $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
            $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.2;
            $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.05;
            $nilai7dospem2 = $nilaiDospem2->nilai_7 * 0.05;
            $nilai8dospem2 = $nilaiDospem2->nilai_8 * 0.3;
            $nilai9dospem2 = $nilaiDospem2->nilai_9 * 0.1;
            $komp1dp2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2;
            $komp2dp2 = $nilai5dospem2 + $nilai6dospem2 + $nilai7dospem2;
            $komp3dp2 = $nilai8dospem2 + $nilai9dospem2;
            } else {
                    // Data tidak ditemukan
                    $nilai1dospem2 = null;
                    $nilai2dospem2 = null;
                    $nilai3dospem2 = null;
                    $nilai4dospem2 = null;
                    $nilai5dospem2 = null;
                    $nilai6dospem2 = null;
                    $nilai7dospem2 = null;
                    $nilai8dospem2 = null;
                    $nilai9dospem2 = null;

                    $komp1dp2 = null;
                    $komp2dp2 = null;
                    $komp3dp2 =  null;
                }

            // nilai penguji 1
            $nilaiPenguji1 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji1Id)
            ->first();
            if ($nilaiPenguji1) {
            $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.2;
            $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.05;
            $nilai7Penguji1 = $nilaiPenguji1->nilai_7 * 0.05;
            $nilai8Penguji1 = $nilaiPenguji1->nilai_8 * 0.3;
            $nilai9Penguji1 = $nilaiPenguji1->nilai_9 * 0.1;
            $komp2pg1 = $nilai5Penguji1 + $nilai6Penguji1 + $nilai7Penguji1;
            $komp3pg1 = $nilai8Penguji1 + $nilai9Penguji1;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji1 = null;
                    $nilai6Penguji1 = null;
                    $nilai7Penguji1 = null;
                    $nilai8Penguji1 = null;
                    $nilai9Penguji1 = null;
                    $komp2pg1 = null;
                    $komp3pg1 =  null;
                }

            // nilai penguji 2
            $nilaiPenguji2 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji2Id)
            ->first();
            if ($nilaiDospem2) {
            $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.2;
            $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.05;
            $nilai7Penguji2 = $nilaiPenguji2->nilai_7 * 0.05;
            $nilai8Penguji2 = $nilaiPenguji2->nilai_8 * 0.3;
            $nilai9Penguji2 = $nilaiPenguji2->nilai_9 * 0.1;
            $komp2pg2= $nilai5Penguji2 + $nilai6Penguji2 + $nilai7Penguji2;
            $komp3pg2 = $nilai8Penguji2 + $nilai9Penguji2;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji2 = null;
                    $nilai6Penguji2 = null;
                    $nilai7Penguji2 = null;
                    $nilai8Penguji2 = null;
                    $nilai9Penguji2 = null;
                    $komp2pg2 = null;
                    $komp3pg2 =  null;
                }

            // nilai penguji 3
            $nilaiPenguji3 = NilaiSidang::where('id_sempro', $id)
            ->where('id_dosen', $penguji3Id)
            ->first();
            if ($nilaiPenguji3) {
            $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.2;
            $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.05;
            $nilai7Penguji3 = $nilaiPenguji3->nilai_7 * 0.05;
            $nilai8Penguji3 = $nilaiPenguji3->nilai_8 * 0.3;
            $nilai9Penguji3 = $nilaiPenguji3->nilai_9 * 0.1;
            $komp2pg3= $nilai5Penguji3 + $nilai6Penguji3 + $nilai7Penguji3;
            $komp3pg3 = $nilai8Penguji3 + $nilai9Penguji3;
            } else {
                    // Data tidak ditemukan
                    $nilai5Penguji3 = null;
                    $nilai6Penguji3 = null;
                    $nilai7Penguji3 = null;
                    $nilai8Penguji3 = null;
                    $nilai9Penguji3 = null;
                    $komp2pg3 = null;
                    $komp3pg3 =  null;
                }
    
            $totalNilaiKeseluruhan = 0;
            $totalRerataNilaiKeseluruhan = 0;
            $jumlahKomponen1 = $komp1dp2 + $komp1dp1;
            $jumlahKomponen2 = $komp2pg3+ $komp2pg2 + $komp2pg1 + $komp2dp1 + $komp2dp2;
            $jumlahKomponen3 = $komp3pg3+ $komp3pg2 + $komp3pg1 + $komp3dp1 + $komp3dp2;
    
    
            // Cek apakah sempro ditemukan
            if (!$sempro) {
                abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
            }
            // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
            return view('/koor/rekapNilaiSidang', compact('sempro', 'nilaiDosen', 'namaDospem2','namaDospem1','namaPenguji1','namaPenguji2',
            'namaPenguji3','jumlahKomponen1','jumlahKomponen2','jumlahKomponen3','komp1dp2','komp2dp2','komp3dp2','komp1dp1','komp2dp1','komp3dp1',
            'komp2pg1','komp3pg1','komp2pg2','komp3pg2','komp2pg3','komp3pg3',));
                
        
    }

    public function exportPDF($id)
    {
        // Load file template .docx
        $templateFilePath = storage_path('app/pen14/pen14.docx');
        $templateProcessor = new TemplateProcessor($templateFilePath);

        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);
        // Ambil ID dosen dari kolom dospem1 pada tabel sempros
        $dp1Id = $sempro->dospem1;
        $dp2Id = $sempro->dospem2;
        $pen1Id= $sempro->penguji1;
        $pen2Id = $sempro->penguji2;
        $pen3Id = $sempro->penguji3;

        // Ambil tanda tangan dosen pembimbing 1
        $ttd1 = Signature::where('user_id', $dp1Id)->first();

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
        $ttd2 = Signature::where('user_id', $dp2Id)->first();

        // Jika tanda tangan ditemukan, dapatkan path tanda tangan
        $ttd2Path = $ttd2 ? public_path('images/' . $ttd2->signature_path) : null;
        // Add the image to the template
        $templateProcessor->setImageValue("ttd2", [
            'path' => $ttd2Path,
            'width' => 80, // Set the width of the image in the document
            'height' => 40, // Set the height of the image in the document
            'ratio' => false, // Set to true to maintain the aspect ratio of the image
        ]);

        // Ambil tanda tangan dosen penguji 1
        $ttd3 = Signature::where('user_id', $pen1Id)->first();

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
        $ttd4 = Signature::where('user_id', $pen2Id)->first();

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
        $ttd5 = Signature::where('user_id', $pen3Id)->first();

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
        $dp1 = User::find($dp1Id);
        $dp2 = User::find($dp2Id);
        $pen1 = User::find($pen1Id);
        $pen2 = User::find($pen2Id);
        $pen3 = User::find($pen3Id);

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


        // Get the id_mahasiswa from the found Sidang record
        $idMahasiswa = $sempro->id_mahasiswa;

        // Get the totalRerataNilaiKeseluruhan using the static method from SemhasController
        $rerataSemhas = SemhasController::calculateAverageRerataNilaiKeseluruhan($idMahasiswa); 
        $twentyFivePercent = $rerataSemhas * 0.25;


        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        //$nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();
        $nilaiDosen = NilaiSidang::where('id_sempro', $id)
            ->with('dosen.signature') // Mengambil data tanda tangan dari tabel signatures yang berhubungan dengan tabel users
            ->get();

        // nilai dospem 1
        $nilaiDospem1 = NilaiSidang::where('id_sempro', $id)
        ->with('dosen.signature')
        ->where('id_dosen', $dp1Id)
        ->first();
        if ($nilaiDospem1) {
        $nilai1dospem1 = $nilaiDospem1->nilai_1 * 0.1;
        $nilai2dospem1 = $nilaiDospem1->nilai_2 * 0.05;
        $nilai3dospem1 = $nilaiDospem1->nilai_3 * 0.05;
        $nilai4dospem1 = $nilaiDospem1->nilai_4 * 0.1;
        $nilai5dospem1 = $nilaiDospem1->nilai_5 * 0.2;
        $nilai6dospem1 = $nilaiDospem1->nilai_6 * 0.05;
        $nilai7dospem1 = $nilaiDospem1->nilai_7 * 0.05;
        $nilai8dospem1 = $nilaiDospem1->nilai_8 * 0.3;
        $nilai9dospem1 = $nilaiDospem1->nilai_9 * 0.1;
        $komp1dp1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1;
        $komp2dp1 = $nilai5dospem1 + $nilai6dospem1 + $nilai7dospem1;
        $komp3dp1 = $nilai8dospem1 + $nilai9dospem1;
        $k2k31 = $komp3dp1 + $komp2dp1;
        
        // nilai dospem 1
        $templateProcessor->setValue("ks1", $komp1dp1);
        $templateProcessor->setValue("k21", $komp2dp1);
        $templateProcessor->setValue("kt1", $komp2dp1);
        $templateProcessor->setValue("tk1", $k2k31);
        } else {
                // Data tidak ditemukan
                $nilai1dospem1 = null;
                $nilai2dospem1 = null;
                $nilai3dospem1 = null;
                $nilai4dospem1 = null;
                $nilai5dospem1 = null;
                $nilai6dospem1 = null;
                $nilai7dospem1 = null;
                $nilai8dospem1 = null;
                $nilai9dospem1 = null;

                $komp1dp1 = null;
                $komp2dp1 = null;
                $komp3dp1 =  null;
            }
        // nilai dospem 2
        $nilaiDospem2 = NilaiSidang::where('id_sempro', $id)
        ->where('id_dosen', $dp2Id)
        ->first();
        if ($nilaiDospem2) {
        $nilai1dospem2 = $nilaiDospem2->nilai_1 * 0.1;
        $nilai2dospem2 = $nilaiDospem2->nilai_2 * 0.05;
        $nilai3dospem2 = $nilaiDospem2->nilai_3 * 0.05;
        $nilai4dospem2 = $nilaiDospem2->nilai_4 * 0.1;
        $nilai5dospem2 = $nilaiDospem2->nilai_5 * 0.2;
        $nilai6dospem2 = $nilaiDospem2->nilai_6 * 0.05;
        $nilai7dospem2 = $nilaiDospem2->nilai_7 * 0.05;
        $nilai8dospem2 = $nilaiDospem2->nilai_8 * 0.3;
        $nilai9dospem2 = $nilaiDospem2->nilai_9 * 0.1;
        $komp1dp2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2;
        $komp2dp2 = $nilai5dospem2 + $nilai6dospem2 + $nilai7dospem2;
        $komp3dp2 = $nilai8dospem2 + $nilai9dospem2;
        $k2k32 = $komp3dp2 + $komp2dp2;

        // nilai dospem 2
        $templateProcessor->setValue("ks2", $komp1dp2);
        $templateProcessor->setValue("k22", $komp2dp2);
        $templateProcessor->setValue("kt2", $komp2dp2);
        $templateProcessor->setValue("tk2", $k2k32);
        } else {
                // Data tidak ditemukan
                $nilai1dospem2 = null;
                $nilai2dospem2 = null;
                $nilai3dospem2 = null;
                $nilai4dospem2 = null;
                $nilai5dospem2 = null;
                $nilai6dospem2 = null;
                $nilai7dospem2 = null;
                $nilai8dospem2 = null;
                $nilai9dospem2 = null;

                $komp1dp2 = null;
                $komp2dp2 = null;
                $komp3dp2 =  null;
            }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSidang::where('id_sempro', $id)
        ->where('id_dosen', $pen1Id)
        ->first();
        if ($nilaiPenguji1) {
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5 * 0.2;
        $nilai6Penguji1 = $nilaiPenguji1->nilai_6 * 0.05;
        $nilai7Penguji1 = $nilaiPenguji1->nilai_7 * 0.05;
        $nilai8Penguji1 = $nilaiPenguji1->nilai_8 * 0.3;
        $nilai9Penguji1 = $nilaiPenguji1->nilai_9 * 0.1;
        $komp2pg1 = $nilai5Penguji1 + $nilai6Penguji1 + $nilai7Penguji1;
        $komp3pg1 = $nilai8Penguji1 + $nilai9Penguji1;

        $k2k33 = $komp3dp2 + $komp2dp2;

        // nilai penguji 1
        $templateProcessor->setValue("k23", $komp2pg1);
        $templateProcessor->setValue("kt3", $komp2pg1);
        $templateProcessor->setValue("tk3", $k2k33);
        } else {
                // Data tidak ditemukan
                $nilai5Penguji1 = null;
                $nilai6Penguji1 = null;
                $nilai7Penguji1 = null;
                $nilai8Penguji1 = null;
                $nilai9Penguji1 = null;
                $komp2pg1 = null;
                $komp3pg1 =  null;
            }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSidang::where('id_sempro', $id)
        ->where('id_dosen', $pen2Id)
        ->first();
        if ($nilaiDospem2) {
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5 * 0.2;
        $nilai6Penguji2 = $nilaiPenguji2->nilai_6 * 0.05;
        $nilai7Penguji2 = $nilaiPenguji2->nilai_7 * 0.05;
        $nilai8Penguji2 = $nilaiPenguji2->nilai_8 * 0.3;
        $nilai9Penguji2 = $nilaiPenguji2->nilai_9 * 0.1;
        $komp2pg2= $nilai5Penguji2 + $nilai6Penguji2 + $nilai7Penguji2;
        $komp3pg2 = $nilai8Penguji2 + $nilai9Penguji2;
        $k2k34 = $komp3dp2 + $komp2dp2;

        // nilai penguji 2
        $templateProcessor->setValue("k24", $komp2pg2);
        $templateProcessor->setValue("kt4", $komp2pg2);
        $templateProcessor->setValue("tk4", $k2k34);
        } else {
                // Data tidak ditemukan
                $nilai5Penguji2 = null;
                $nilai6Penguji2 = null;
                $nilai7Penguji2 = null;
                $nilai8Penguji2 = null;
                $nilai9Penguji2 = null;
                $komp2pg2 = null;
                $komp3pg2 =  null;
            }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSidang::where('id_sempro', $id)
        ->where('id_dosen', $pen3Id)
        ->first();
        if ($nilaiPenguji3) {
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5 * 0.2;
        $nilai6Penguji3 = $nilaiPenguji3->nilai_6 * 0.05;
        $nilai7Penguji3 = $nilaiPenguji3->nilai_7 * 0.05;
        $nilai8Penguji3 = $nilaiPenguji3->nilai_8 * 0.3;
        $nilai9Penguji3 = $nilaiPenguji3->nilai_9 * 0.1;
        $komp2pg3= $nilai5Penguji3 + $nilai6Penguji3 + $nilai7Penguji3;
        $komp3pg3 = $nilai8Penguji3 + $nilai9Penguji3;
        $k2k35 = $komp3pg3 + $komp2pg3;

        // nilai penguji 3
        $templateProcessor->setValue("k25", $komp2pg3);
        $templateProcessor->setValue("kt5", $komp2pg3);
        $templateProcessor->setValue("tk5", $k2k35);
        } else {
                // Data tidak ditemukan
                $nilai5Penguji3 = null;
                $nilai6Penguji3 = null;
                $nilai7Penguji3 = null;
                $nilai8Penguji3 = null;
                $nilai9Penguji3 = null;
                $komp2pg3 = null;
                $komp3pg3 =  null;
            }

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;
        $jumlahKomponen1 = $komp1dp2 + $komp1dp1;
        $jumlahKomponen2 = $komp2pg3+ $komp2pg2 + $komp2pg1 + $komp2dp1 + $komp2dp2;
        $jumlahKomponen3 = $komp3pg3+ $komp3pg2 + $komp3pg1 + $komp3dp1 + $komp3dp2;

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;

        $totalKomponen32 = $jumlahKomponen3 + $jumlahKomponen2;

        $rerataKom1 = $jumlahKomponen1 / 2;
        $rerataKom32 = $totalKomponen32 / 5;

        $C=$rerataKom32 + $rerataKom1;
        $seventyFivePercent=$C * 0.75;
        $TotalCD=$seventyFivePercent + $twentyFivePercent;

        // Ambil data tanda tangan dosen untuk ditampilkan di halaman pen05
        $tandaTanganDosens = [];

        foreach ($nilaiDosen as $nilai) {
            // Retrieve the signature for each dosen using the relationship
            $signature = $nilai->dosen->signature;

            // Check if the signature exists and get the path
            $tandaTanganPath = $signature ? public_path('images/' . $signature->signature_path) : null;

            $tandaTanganDosens[] = $tandaTanganPath;
        }

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

        

        // Isi data nilai dosen ke dalam tabel di template .docx
        foreach ($nilaiDosen as $index => $nilai) {
            $tableRowIndex = $index + 1;
            // Check if the signature exists
            if ($tandaTanganDosens[$index]) {
                $signaturePath = $tandaTanganDosens[$index];

                // Add the image to the template
                $templateProcessor->setImageValue("signature{$tableRowIndex}", [
                    'path' => $signaturePath,
                    'width' => 80, // Set the width of the image in the document
                    'height' => 40, // Set the height of the image in the document
                    'ratio' => false, // Set to true to maintain the aspect ratio of the image
                ]);
            }
        }

        // Tambahkan variabel total nilai keseluruhan dan rerata nilai keseluruhan ke dalam template Word
        $templateProcessor->setValue('rr32', $rerataKom32);
        $templateProcessor->setValue('rr1', $rerataKom1);
        $templateProcessor->setValue('C', $C);
        $templateProcessor->setValue('bc', $seventyFivePercent);
        $templateProcessor->setValue('jk1', $jumlahKomponen1);
        $templateProcessor->setValue('jk32', $totalKomponen32);
        $templateProcessor->setValue('sem', $rerataSemhas);
        $templateProcessor->setValue('bs', $twentyFivePercent);
        $templateProcessor->setValue('tot', $TotalCD);
        

        // Set the status of penguji1, penguji2, and penguji3 based on dospem1 and dospem2 roles
        $templateProcessor->setValue("penguji1_status", "Anggota");
        $templateProcessor->setValue("penguji2_status", "Anggota");
        $templateProcessor->setValue("penguji3_status", "Anggota");

        
        // Add the signature for dospem2 at the bottom of the document
        if ($tandaTanganDosens[1]) {
            $signaturePathDospem2 = $tandaTanganDosens[1];

            // Add the image to the template for dospem2
            $templateProcessor->setImageValue('dospem2_signature', [
                'path' => $signaturePathDospem2,
                'width' => 80, // Set the width of the image in the document
                'height' => 40, // Set the height of the image in the document
                'ratio' => false, // Set to true to maintain the aspect ratio of the image
            ]);
        }

        // Add the condition for "LULUS" or "TIDAK LULUS" based on the totalRerataNilaiKeseluruhan
    $statusLulus = $TotalCD > 50 ? 'LULUS' : 'TIDAK LULUS';

    // Modify the template based on status
    if ($statusLulus === 'LULUS') {
        $templateProcessor->setValue('kelulusan1', $statusLulus);
        $templateProcessor->setValue('kelulusan2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'TIDAK LULUS' . '</w:t></w:r>');
    } else {
        $templateProcessor->setValue('kelulusan1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'LULUS' . '</w:t></w:r>');
        $templateProcessor->setValue('kelulusan2', $statusLulus);
    }


    if ($TotalCD >= 87) {
        $templateProcessor->setValue('cat1', 'A');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD >= 78 && $TotalCD < 87) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', 'AB');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD >= 69 && $TotalCD < 78) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', 'B');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD >= 60 && $TotalCD < 69) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', 'BC');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD >= 51 && $TotalCD < 60) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', 'C');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD >= 41 && $TotalCD < 51) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', 'D');
        $templateProcessor->setValue('cat7', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'E' . '</w:t></w:r>');
    } elseif ($TotalCD < 41) {
        $templateProcessor->setValue('cat1', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'A' . '</w:t></w:r>');
        $templateProcessor->setValue('cat2', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'AB' . '</w:t></w:r>');
        $templateProcessor->setValue('cat3', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'B' . '</w:t></w:r>');
        $templateProcessor->setValue('cat4', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'BC' . '</w:t></w:r>');
        $templateProcessor->setValue('cat5', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'C' . '</w:t></w:r>');
        $templateProcessor->setValue('cat6', '<w:r><w:rPr><w:strike/></w:rPr><w:t>' . 'D' . '</w:t></w:r>');
        $templateProcessor->setValue('cat7', 'E');
    }

        

        // Simpan file .docx yang sudah diisi data
        $outputFileName = "{$sempro->nama}_pen14.docx"; // Nama file sesuai dengan nama mahasiswa
        $outputFilePath = storage_path('app/pen14/') . $outputFileName;
        $templateProcessor->saveAs($outputFilePath);

        // Download the generated Docx file
        //return response()->download($outputFilePath, $outputFileName);
        // Redirect pengguna ke halaman untuk melihat file Word
        //return redirect()->route('koor.home');

        // Convert the Word document to PDF using Dompdf
        // Convert the Word document to HTML using PhpWord
        // Load the DOCX file

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
        $pdfOutputFileName = "{$sempro->nama}_pen14.pdf"; // Nama file sesuai dengan nama mahasiswa
        $pdfOutputFilePath = storage_path('app/pen14/') . $pdfOutputFileName;
        file_put_contents($pdfOutputFilePath, $pdfContents);

        // Hapus file .docx setelah diubah ke PDF
        unlink($outputFilePath);

        // Redirect pengguna ke halaman untuk melihat file PDF
        //return redirect()->back()->with('success', 'File PDF berhasil di-generate');


        // atau Download the generated PDF file
        return response()->download($pdfOutputFilePath, $pdfOutputFileName);
      
    }
}
