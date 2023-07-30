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
        $dosens = User::where('role', 1 )->get(['id', 'name']);

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
            'seminar'   => 'seminar proposal', // Set default value for 'seminar'
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
        return redirect()->route('dosen.home')->with('success', 'Nilai berhasil disimpan');
    }

    public function rekapNilai($id)
    {
        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);

        // Ambil ID dosen dari kolom dospem1 pada tabel sempros
        $dospem1Id = $sempro->dospem1;
        $dospem2Id = $sempro->dospem2;

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;

        // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $nilai) {
            $totalNilai = $nilai->nilai_1 + $nilai->nilai_2 + $nilai->nilai_3 + $nilai->nilai_4 + $nilai->nilai_5;
            $totalNilai1 = $totalNilai / 5;
            $nilai->total_nilai = $totalNilai1;

            // Tambahkan nilai pada total nilai keseluruhan
            $totalNilaiKeseluruhan += $totalNilai1;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
        }     

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }
        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
        return view('/koor/rekapNilaiSempro', compact('sempro', 'nilaiDosen', 'totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan', 'dospem1Id','dospem2Id'));
            
    }



    public function exportPDF($id)
    {
        // Load file template .docx
        $templateFilePath = storage_path('app/pen05/pen05.docx');
        $templateProcessor = new TemplateProcessor($templateFilePath);

        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        //$nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)
            ->with('dosen.signature') // Mengambil data tanda tangan dari tabel signatures yang berhubungan dengan tabel users
            ->get();

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;

        // Ambil data tanda tangan dosen untuk ditampilkan di halaman pen05
        $tandaTanganDosens = [];

        foreach ($nilaiDosen as $nilai) {
            // Retrieve the signature for each dosen using the relationship
            $signature = $nilai->dosen->signature;

            // Check if the signature exists and get the path
            $tandaTanganPath = $signature ? public_path('images/' . $signature->signature_path) : null;

            $tandaTanganDosens[] = $tandaTanganPath;
        }

        // Simpan dospem1 dan dospem2 ke dalam variabel terpisah
        $dospem1Name = '';
        $dospem2Name = '';

        // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $index => $nilai) {
            $tableRowIndex = $index + 1;
            $templateProcessor->setValue("dosen{$tableRowIndex}", $nilai->dosen->name);

            // Tentukan status (Sekretaris, Ketua, atau Anggota) untuk masing-masing dosen
            if ($nilai->dosen->id === $nilai->dospem1) {
                $templateProcessor->setValue("status{$tableRowIndex}", 'Sekretaris');
                $dospem1Name = $nilai->dosen->name; // Simpan nama dospem1 untuk digunakan nanti
            } elseif ($nilai->dosen->id === $nilai->dospem2) {
                $templateProcessor->setValue("status{$tableRowIndex}", 'Ketua');
                $dospem2Name = $nilai->dosen->name; // Simpan nama dospem2 untuk digunakan nanti
            } else {
                $templateProcessor->setValue("status{$tableRowIndex}", 'Anggota');
            }

            // Hitung total nilai untuk masing-masing dosen dan tambahkan ke total keseluruhan
            $totalNilai = $nilai->nilai_1 + $nilai->nilai_2 + $nilai->nilai_3 + $nilai->nilai_4 + $nilai->nilai_5;
            $totalNilai1 = $totalNilai / 5;
            $nilai->total_nilai = $totalNilai1;

            // Tambahkan nilai pada total nilai keseluruhan
            $totalNilaiKeseluruhan += $totalNilai1;
            
        $templateProcessor->setValue("dospem1_name", $dospem1Name);
        $templateProcessor->setValue("dospem2_name", $dospem2Name);
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

        // Isi data nilai dosen ke dalam tabel di template .docx
        foreach ($nilaiDosen as $index => $nilai) {
            $tableRowIndex = $index + 1;
            $templateProcessor->setValue("nilai{$tableRowIndex}", $nilai->total_nilai);
            $templateProcessor->setValue("nip{$tableRowIndex}", $nilai->dosen->nim);
            // Check if the signature exists
            if ($tandaTanganDosens[$index]) {
                $signaturePath = $tandaTanganDosens[$index];

                // Add the image to the template
                $templateProcessor->setImageValue("signature{$tableRowIndex}", [
                    'path' => $signaturePath,
                    'width' => 100, // Set the width of the image in the document
                    'height' => 50, // Set the height of the image in the document
                    'ratio' => false, // Set to true to maintain the aspect ratio of the image
                ]);
            }
        }

        

        $templateProcessor->setValue("dospem1_name", $dospem1Name);
        $templateProcessor->setValue("dospem2_name", $dospem2Name);

        // Tambahkan variabel total nilai keseluruhan dan rerata nilai keseluruhan ke dalam template Word
        $templateProcessor->setValue('total', $totalNilaiKeseluruhan);
        $templateProcessor->setValue('rerata', $totalRerataNilaiKeseluruhan);

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
                'width' => 100, // Set the width of the image in the document
                'height' => 50, // Set the height of the image in the document
                'ratio' => false, // Set to true to maintain the aspect ratio of the image
            ]);
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
        $clientId = "23851de1-2193-4970-87a1-e5c4bd6f8aa9";
        $clientSecret = "622a54584d6716c6e529fbbee20c107f";
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

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        $nilaiDosen = NilaiSempro::where('id_sempro', $id)->get();

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;

        // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $nilai) {
            $totalNilai = $nilai->nilai_1 + $nilai->nilai_2 + $nilai->nilai_3 + $nilai->nilai_4 + $nilai->nilai_5;
            $totalNilai1 = $totalNilai / 5;
            $nilai->total_nilai = $totalNilai1;

            // Tambahkan nilai pada total nilai keseluruhan
            $totalNilaiKeseluruhan += $totalNilai1;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
        }     

        // Cek apakah sempro ditemukan
        if (!$sempro) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }


        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro
        return view('/dosen/pen05', compact('sempro','nilaiDosen','totalNilaiKeseluruhan','totalRerataNilaiKeseluruhan'));
    }

    

    public function sendDataToCoordinator()
    {
        // Ambil data sempro berdasarkan ID dosen yang sedang login
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem2', $dosenId)->get();

        // Ubah nilai status_nilai menjadi "selesai dinilai" untuk setiap sempro
        foreach ($sempros as $sempro) {
            $sempro->status_nilai = 'selesai dinilai';
            $sempro->save();
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

        //find sempro by ID
        $sempro = Sempro::find($id);

        //delete post
        $sempro->delete();

        //return response
        return new Resource(true, 'Data Sempro Berhasil Dihapus!', null);
    }

    
}
