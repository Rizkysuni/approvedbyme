<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Sempro;
use App\Models\User;
use App\Models\NilaiSempro;
use App\Models\Signature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Aspose\Words\WordsApi;
use Aspose\Words\Model\Requests\ConvertDocumentRequest;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

//import Resource
use App\Http\Resources\Resource;

//import Facade "File"
use Illuminate\Support\Facades\File;

//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

use TCPDF;

use setasign\Fpdi\Tcpdf\Fpdi;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class SemproController extends Controller
{
    /**
    * index
    *
    * @return void
    */
   public function index()
   {
       //get all sempro
       $sempro= Sempro::latest()->paginate(5);

       //return collection of sempros as a resource
       return new Resource(true, 'List Data Sempro', $sempro);
   }

   /**
     * create view
     *
     * @param  mixed $request
     * @return void
     */
   public function create()
    {
        // Mendapatkan daftar dosen
        $dosens = User::whereIn('role', [1, 2])->get(['id', 'name']);

        return view('mahasiswa.create', compact('dosens'));
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
            'seminar'   => 'Seminar Proposal', // Set default value for 'seminar'
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
        return view('/dosen/beriNilaiSempro', compact('sempro'));
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
            // Validasi komponen penilaian lainnya
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan nilai ke dalam tabel nilaiSempro
        $nilaiSempro = NilaiSempro::create([
            'id_sempro' => $request->id_sempro,
            'id_dosen'  => $request->id_dosen,
            'nilai_1'   => $request->nilai_1,
            'nilai_2'   => $request->nilai_2,            
            'nilai_3'   => $request->nilai_3,
            'nilai_4'   => $request->nilai_4,            
            'nilai_5'   => $request->nilai_5,
            // Simpan komponen penilaian lainnya
        ]);

        // Redirect atau tampilkan notifikasi sesuai kebutuhan
        if (auth()->user()->role == 'dosen') {
            return redirect()->route('dosen.home')->with('success', 'Nilai berhasil disimpan');
        } elseif (auth()->user()->role == 'koordinator') {
            return redirect()->route('koor.home')->with('success', 'Nilai berhasil disimpan');
        }
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
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();

        
        // nilai dospem 1
        $nilaiDospem1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem1Id)
        ->first();
        if ($nilaiDospem1) {

        $nilai1dospem1 = $nilaiDospem1->nilai_1;
        $nilai2dospem1 = $nilaiDospem1->nilai_2;
        $nilai3dospem1 = $nilaiDospem1->nilai_3;
        $nilai4dospem1 = $nilaiDospem1->nilai_4;
        $nilai5dospem1 = $nilaiDospem1->nilai_5;
        $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1;
        $totrat1 = $tot1 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1dospem1 = null;
            $nilai2dospem1 = null;
            $nilai3dospem1 = null;
            $nilai4dospem1 = null;
            $nilai5dospem1 = null;

            $tot1 = null;
            $totrat1 = null;
        }

        $nilaiDospem2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem2Id)
        ->first(); // Mengambil item pertama dari hasil query

        if ($nilaiDospem2) {
            // Data ditemukan
            $nilai1dospem2 = $nilaiDospem2->nilai_1;
            $nilai2dospem2 = $nilaiDospem2->nilai_2;
            $nilai3dospem2 = $nilaiDospem2->nilai_3;
            $nilai4dospem2 = $nilaiDospem2->nilai_4;
            $nilai5dospem2 = $nilaiDospem2->nilai_5;

            $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2;
            $totrat2 = $tot2 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1dospem2 = null;
            $nilai2dospem2 = null;
            $nilai3dospem2 = null;
            $nilai4dospem2 = null;
            $nilai5dospem2 = null;

            $tot2 = null;
            $totrat2 = null;
        }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji1Id)
        ->first();
        if ($nilaiPenguji1) {

        $nilai1Penguji1 = $nilaiPenguji1->nilai_1;
        $nilai2Penguji1 = $nilaiPenguji1->nilai_2;
        $nilai3Penguji1 = $nilaiPenguji1->nilai_3;
        $nilai4Penguji1 = $nilaiPenguji1->nilai_4;
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5;

        $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1;
        $totrat3 = $tot3 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji1 = null;
            $nilai2Penguji1 = null;
            $nilai3Penguji1 = null;
            $nilai4Penguji1 = null;
            $nilai5Penguji1 = null;

            $tot3 = null;
            $totrat3 = null;
        }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji2Id)
        ->first();
        if ($nilaiPenguji2) {
            
        $nilai1Penguji2 = $nilaiPenguji2->nilai_1;
        $nilai2Penguji2 = $nilaiPenguji2->nilai_2;
        $nilai3Penguji2 = $nilaiPenguji2->nilai_3;
        $nilai4Penguji2 = $nilaiPenguji2->nilai_4;
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5;
        $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2 + $nilai4Penguji2 + $nilai5Penguji2;
        $totrat4 = $tot4 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji2 = null;
            $nilai2Penguji2 = null;
            $nilai3Penguji2 = null;
            $nilai4Penguji2 = null;
            $nilai5Penguji2 = null;

            $tot4 = null;
            $totrat4 = null;
        }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji3Id)
        ->first();
        if ($nilaiPenguji3) {
        $nilai1Penguji3 = $nilaiPenguji3->nilai_1;
        $nilai2Penguji3 = $nilaiPenguji3->nilai_2;
        $nilai3Penguji3 = $nilaiPenguji3->nilai_3;
        $nilai4Penguji3 = $nilaiPenguji3->nilai_4;
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5;

        $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3 + $nilai4Penguji3 + $nilai5Penguji3;
        $totrat5 = $tot5 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji3 = null;
            $nilai2Penguji3 = null;
            $nilai3Penguji3 = null;
            $nilai4Penguji3 = null;
            $nilai5Penguji3 = null;

            $tot5 = null;
            $totrat5 = null;
        }

        $totalNilaiKeseluruhan = $totrat5 + $totrat4 + $totrat3 + $totrat2 + $totrat1;
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
     

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }
        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
        return view('/koor/rekapNilaiSempro', compact('sempro', 'nilaiDosen', 'totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan',
         'totrat5', 'totrat4', 'totrat3','totrat2','totrat1','namaDospem1','namaDospem2','namaPenguji1','namaPenguji2','namaPenguji3'));
            
    }



    public function exportPDF($id)
    {
        // Load file template .docx
        $templateFilePath = storage_path('app/pen05/pen05.docx');
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
        $ttd5 = Signature::where('user_id', $penguji2Id)->first();

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
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)
            ->with('dosen.signature') // Mengambil data tanda tangan dari tabel signatures yang berhubungan dengan tabel users
            ->get();

        // nilai dospem 1
        $nilaiDospem1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem1Id)
        ->first();
        if ($nilaiDospem1) {

        $nilai1dospem1 = $nilaiDospem1->nilai_1;
        $nilai2dospem1 = $nilaiDospem1->nilai_2;
        $nilai3dospem1 = $nilaiDospem1->nilai_3;
        $nilai4dospem1 = $nilaiDospem1->nilai_4;
        $nilai5dospem1 = $nilaiDospem1->nilai_5;
        $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1;
        $totrat1 = $tot1 / 5;
        
        $templateProcessor->setValue("tot1", $totrat1);
        } else {
            // Data tidak ditemukan
            $nilai1dospem1 = null;
            $nilai2dospem1 = null;
            $nilai3dospem1 = null;
            $nilai4dospem1 = null;
            $nilai5dospem1 = null;

            $tot1 = null;
            $totrat1 = null;
        }

        $nilaiDospem2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem2Id)
        ->first(); // Mengambil item pertama dari hasil query

        if ($nilaiDospem2) {
            // Data ditemukan
            $nilai1dospem2 = $nilaiDospem2->nilai_1;
            $nilai2dospem2 = $nilaiDospem2->nilai_2;
            $nilai3dospem2 = $nilaiDospem2->nilai_3;
            $nilai4dospem2 = $nilaiDospem2->nilai_4;
            $nilai5dospem2 = $nilaiDospem2->nilai_5;

            $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2;
            $totrat2 = $tot2 / 5;

            $templateProcessor->setValue("tot2", $totrat2);
        } else {
            // Data tidak ditemukan
            $nilai1dospem2 = null;
            $nilai2dospem2 = null;
            $nilai3dospem2 = null;
            $nilai4dospem2 = null;
            $nilai5dospem2 = null;

            $tot2 = null;
            $totrat2 = null;
        }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji1Id)
        ->first();
        if ($nilaiPenguji1) {

        $nilai1Penguji1 = $nilaiPenguji1->nilai_1;
        $nilai2Penguji1 = $nilaiPenguji1->nilai_2;
        $nilai3Penguji1 = $nilaiPenguji1->nilai_3;
        $nilai4Penguji1 = $nilaiPenguji1->nilai_4;
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5;

        $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1;
        $totrat3 = $tot3 / 5;
        $templateProcessor->setValue("tot3", $totrat3);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji1 = null;
            $nilai2Penguji1 = null;
            $nilai3Penguji1 = null;
            $nilai4Penguji1 = null;
            $nilai5Penguji1 = null;

            $tot3 = null;
            $totrat3 = null;
        }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji2Id)
        ->first();
        if ($nilaiPenguji2) {
            
        $nilai1Penguji2 = $nilaiPenguji2->nilai_1;
        $nilai2Penguji2 = $nilaiPenguji2->nilai_2;
        $nilai3Penguji2 = $nilaiPenguji2->nilai_3;
        $nilai4Penguji2 = $nilaiPenguji2->nilai_4;
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5;
        $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2 + $nilai4Penguji2 + $nilai5Penguji2;
        $totrat4 = $tot4 / 5;
        $templateProcessor->setValue("tot4", $totrat4);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji2 = null;
            $nilai2Penguji2 = null;
            $nilai3Penguji2 = null;
            $nilai4Penguji2 = null;
            $nilai5Penguji2 = null;

            $tot4 = null;
            $totrat4 = null;
        }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji3Id)
        ->first();
        if ($nilaiPenguji3) {
        $nilai1Penguji3 = $nilaiPenguji3->nilai_1;
        $nilai2Penguji3 = $nilaiPenguji3->nilai_2;
        $nilai3Penguji3 = $nilaiPenguji3->nilai_3;
        $nilai4Penguji3 = $nilaiPenguji3->nilai_4;
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5;

        $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3 + $nilai4Penguji3 + $nilai5Penguji3;
        $totrat5 = $tot5 / 5;
        $templateProcessor->setValue("tot5", $totrat5);
        } else {
            // Data tidak ditemukan
            $nilai1Penguji3 = null;
            $nilai2Penguji3 = null;
            $nilai3Penguji3 = null;
            $nilai4Penguji3 = null;
            $nilai5Penguji3 = null;

            $tot5 = null;
            $totrat5 = null;
        }

        $totalNilaiKeseluruhan = $totrat5 + $totrat4 + $totrat3 + $totrat2 + $totrat1;
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
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
    $statusLulus = $totalRerataNilaiKeseluruhan > 68 ? 'LULUS /' : '/ TIDAK LULUS';

    // Modify the template based on status
    if ($statusLulus === 'LULUS /') {
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
        $outputFileName = "{$sempro->nama}_pen05.docx"; // Nama file sesuai dengan nama mahasiswa
        $outputFilePath = storage_path('app/pen05/') . $outputFileName;
        $templateProcessor->saveAs($outputFilePath);

        // Download the generated Docx file
        // return response()->download($outputFilePath, $outputFileName);
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
        $pdfOutputFileName = "{$sempro->nama}_pen05.pdf"; // Nama file sesuai dengan nama mahasiswa
        $pdfOutputFilePath = storage_path('app/pen05/') . $pdfOutputFileName;
        file_put_contents($pdfOutputFilePath, $pdfContents);

        // Hapus file .docx setelah diubah ke PDF
        //unlink($outputFilePath);

        // Redirect pengguna ke halaman untuk melihat file PDF
        //return redirect()->back()->with('success', 'File PDF berhasil di-generate');

        // Simpan nama file sementara dalam session agar bisa diakses di halaman lain
        session(['pdfOutputFileName' => $pdfOutputFileName]);
        session(['pdfOutputFilePath' => $pdfOutputFilePath]);

        // atau Download the generated PDF file
        return response()->download($pdfOutputFilePath, $pdfOutputFileName);

        // Simpan nama file sementara dalam session agar bisa diakses di halaman lain
        //return view('koor.preview_pdf', compact('pdfOutputFileName','pdfOutputFilePath'));

      
    }

    // public function previewPdf(Request $request)
    // {
    //     $filename = $request->query('filename');
    //     $filepath = $request->query('filepath');
    
    //     // Validate the file and ensure it exists in the specified filepath
    //     if (!empty($filename) && !empty($filepath) && file_exists($filepath)) {
    //         // Set the appropriate headers to display the PDF in the browser
    //         return response()->file($filepath, ['Content-Disposition' => "inline; filename=\"{$filename}\""]);
    //     } else {
    //         return response()->json(['error' => 'File not found.']);
    //     }
    // }   

    public function pen05Home()
    {
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem2', $dosenId)
                        ->Where('status_nilai', 'belum dinilai')
                        ->Where('seminar', 'seminar proposal')
                    ->get();

        return view('dosen.pen05Home', compact('sempros'));
    }

    public function pen05($id)
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
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();

        
        // nilai dospem 1
        $nilaiDospem1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem1Id)
        ->first();
        if ($nilaiDospem1) {

        $nilai1dospem1 = $nilaiDospem1->nilai_1;
        $nilai2dospem1 = $nilaiDospem1->nilai_2;
        $nilai3dospem1 = $nilaiDospem1->nilai_3;
        $nilai4dospem1 = $nilaiDospem1->nilai_4;
        $nilai5dospem1 = $nilaiDospem1->nilai_5;
        $tot1 = $nilai1dospem1 + $nilai2dospem1 + $nilai3dospem1 + $nilai4dospem1 + $nilai5dospem1;
        $totrat1 = $tot1 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1dospem1 = null;
            $nilai2dospem1 = null;
            $nilai3dospem1 = null;
            $nilai4dospem1 = null;
            $nilai5dospem1 = null;

            $tot1 = null;
            $totrat1 = null;
        }

        $nilaiDospem2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $dospem2Id)
        ->first(); // Mengambil item pertama dari hasil query

        if ($nilaiDospem2) {
            // Data ditemukan
            $nilai1dospem2 = $nilaiDospem2->nilai_1;
            $nilai2dospem2 = $nilaiDospem2->nilai_2;
            $nilai3dospem2 = $nilaiDospem2->nilai_3;
            $nilai4dospem2 = $nilaiDospem2->nilai_4;
            $nilai5dospem2 = $nilaiDospem2->nilai_5;

            $tot2 = $nilai1dospem2 + $nilai2dospem2 + $nilai3dospem2 + $nilai4dospem2 + $nilai5dospem2;
            $totrat2 = $tot2 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1dospem2 = null;
            $nilai2dospem2 = null;
            $nilai3dospem2 = null;
            $nilai4dospem2 = null;
            $nilai5dospem2 = null;

            $tot2 = null;
            $totrat2 = null;
        }

        // nilai penguji 1
        $nilaiPenguji1 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji1Id)
        ->first();
        if ($nilaiPenguji1) {

        $nilai1Penguji1 = $nilaiPenguji1->nilai_1;
        $nilai2Penguji1 = $nilaiPenguji1->nilai_2;
        $nilai3Penguji1 = $nilaiPenguji1->nilai_3;
        $nilai4Penguji1 = $nilaiPenguji1->nilai_4;
        $nilai5Penguji1 = $nilaiPenguji1->nilai_5;

        $tot3 = $nilai1Penguji1 + $nilai2Penguji1 + $nilai3Penguji1 + $nilai4Penguji1 + $nilai5Penguji1;
        $totrat3 = $tot3 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji1 = null;
            $nilai2Penguji1 = null;
            $nilai3Penguji1 = null;
            $nilai4Penguji1 = null;
            $nilai5Penguji1 = null;

            $tot3 = null;
            $totrat3 = null;
        }

        // nilai penguji 2
        $nilaiPenguji2 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji2Id)
        ->first();
        if ($nilaiPenguji2) {
            
        $nilai1Penguji2 = $nilaiPenguji2->nilai_1;
        $nilai2Penguji2 = $nilaiPenguji2->nilai_2;
        $nilai3Penguji2 = $nilaiPenguji2->nilai_3;
        $nilai4Penguji2 = $nilaiPenguji2->nilai_4;
        $nilai5Penguji2 = $nilaiPenguji2->nilai_5;
        $tot4 = $nilai1Penguji2 + $nilai2Penguji2 + $nilai3Penguji2 + $nilai4Penguji2 + $nilai5Penguji2;
        $totrat4 = $tot4 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji2 = null;
            $nilai2Penguji2 = null;
            $nilai3Penguji2 = null;
            $nilai4Penguji2 = null;
            $nilai5Penguji2 = null;

            $tot4 = null;
            $totrat4 = null;
        }

        // nilai penguji 3
        $nilaiPenguji3 = NilaiSempro::where('id_sempro', $id)
        ->where('id_dosen', $penguji3Id)
        ->first();
        if ($nilaiPenguji3) {
        $nilai1Penguji3 = $nilaiPenguji3->nilai_1;
        $nilai2Penguji3 = $nilaiPenguji3->nilai_2;
        $nilai3Penguji3 = $nilaiPenguji3->nilai_3;
        $nilai4Penguji3 = $nilaiPenguji3->nilai_4;
        $nilai5Penguji3 = $nilaiPenguji3->nilai_5;

        $tot5 = $nilai1Penguji3 + $nilai2Penguji3 + $nilai3Penguji3 + $nilai4Penguji3 + $nilai5Penguji3;
        $totrat5 = $tot5 / 5;
        } else {
            // Data tidak ditemukan
            $nilai1Penguji3 = null;
            $nilai2Penguji3 = null;
            $nilai3Penguji3 = null;
            $nilai4Penguji3 = null;
            $nilai5Penguji3 = null;

            $tot5 = null;
            $totrat5 = null;
        }

        $totalNilaiKeseluruhan = $totrat5 + $totrat4 + $totrat3 + $totrat2 + $totrat1;
        $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
     

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }
        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
        return view('/dosen/pen05', compact('sempro', 'nilaiDosen', 'totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan',
         'totrat5', 'totrat4', 'totrat3','totrat2','totrat1','namaDospem1','namaDospem2','namaPenguji1','namaPenguji2','namaPenguji3'));
    }

    

    public function sendDataToCoordinator()
    {
        // Ambil data sempro berdasarkan ID dosen yang sedang login
        $dosenId = auth()->user()->id;
        $semproList = Sempro::where('dospem2', $dosenId)
                            ->where('seminar', 'seminar proposal')
                            ->get();

        // Ubah nilai status_nilai menjadi "selesai dinilai" untuk setiap sempro
        foreach ($semproList as $sempros) {
            $sempros->status_nilai = 'selesai dinilai';
            $sempros->save();
        }

        // Redirect kembali ke halaman pen05Home setelah nilai berhasil diubah
        return redirect()->route('dosen.pen05Home')
            ->with('success', 'Data berhasil dikirim ke koordinator!');
    }

    /**
     * show
     *
     * @param  mixed $sempro
     * @return void
     */
    public function show($id)
    {
        //find sempro by ID
        $sempro = Sempro::find($id);

        //return single sempro as a resource
        return new Resource(true, 'Detail Data sempro!', $sempro);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $sempro
     * @return void
     */
    public function updateNilai(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nilai01'   => 'required|numeric',
            'nilai02'   => 'required|numeric',
            'nilai03'   => 'required|numeric',
            'nilai04'   => 'required|numeric',
            'nilai05'   => 'required|numeric',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find sempro by ID
        $sempro = Sempro::find($id);

        
        //update sempro
        $sempro->update([
            'nilai01'   => $request->nilai01,
            'nilai02'   => $request->nilai02,
            'nilai03'   => $request->nilai03,
            'nilai04'   => $request->nilai04,
            'nilai05'   => $request->nilai05,
        ]);

        // Isi ID dosen
        //$sempro->id_dosen = Auth::user()->id; // Misalnya, mengambil ID dosen dari user yang sedang melakukan input

        //return response
        return new Resource(true, 'Nilai Berhasil Ditambahkan!', $sempro);
    }

    /**
     * destroy
     *
     * @param  mixed $sempro
     * @return void
     */
    public function destroy($id)
    {
        // Temukan dan hapus data seminar berdasarkan ID
        $sempro = Sempro::findOrFail($id);
        $sempro->delete();

        // Redirect ke halaman index atau halaman lainnya
        return redirect()->route('admin.home')->with('success', 'Seminar deleted successfully');
    }

    /**
     * edit
     *
     * @param  mixed $sempro
     * @return void
     */
    public function edit($id)
    {
        $sempro = Sempro::findOrFail($id); // Mengambil data sempro berdasarkan ID

        // Mendapatkan daftar dosen
        $dosens = User::whereIn('role', [1, 2])->get(['id', 'name']);

        return view('admin.edit', compact('sempro','dosens'));
    }

    /**
     * update
     *
     * @param  mixed $sempro
     * @return void
     */
    public function update(Request $request, $id)
    {
        $sempro = Sempro::findOrFail($id); // Mengambil data sempro berdasarkan ID

        // Validasi input
        $request->validate([
            // Aturan validasi sesuai atribut pada tabel sempro
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
            'seminar'       => 'required',
            'status_nilai'  => 'required',
        ]);

        // Update data sempro dengan data baru dari input
        $sempro->update($request->all());

        // Anda dapat menambahkan logika lain di sini, seperti pengecekan peran admin

        return redirect()->route('admin.home')->with('success', 'Data sempro berhasil diperbarui.');
    }
    
}
