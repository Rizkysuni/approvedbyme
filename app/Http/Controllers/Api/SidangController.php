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

        $sempros = Sempro::where('id_mahasiswa', $user->id)
            ->where('seminar', 'Seminar Hasil')
            ->first();

       return view('mahasiswa.createSidang', compact('sempros'));
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
           'seminar'   => 'Sidang Akhir', // Set default value for 'seminar'
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
            'nilai_1'   => 'required',
            'nilai_2'   => 'required',
            'nilai_3'   => 'required',
            'nilai_4'   => 'required',
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
        return redirect()->route('dosen.home')->with('success', 'Nilai berhasil disimpan');
    }

    public function pen14Home()
    {
        $dosenId = auth()->user()->id;
        $sidang = Sempro::where('dospem2', $dosenId)
                        ->Where('status_nilai', 'belum dinilai')
                        ->Where('seminar', 'Sidang Akhir')
                    ->get();

        return view('dosen.pen14Home', compact('sidang'));
    }

    public function pen14($id)
    {
        // Ambil data sempro berdasarkan ID
        $sidang = Sempro::find($id);

        // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
        $nilaiDosen = NilaiSidang::where('id_sempro', $id)->get();

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;
        $jumlahKomponen1 = 0;
        $jumlahKomponen2 = 0;
        $jumlahKomponen3 = 0;

        // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $nilai) {
            // Apply the weights to each nilai_x
            $nilai_1_weighted = $nilai->nilai_1 * 0.1;
            $nilai_2_weighted = $nilai->nilai_2 * 0.05;
            $nilai_3_weighted = $nilai->nilai_3 * 0.05;
            $nilai_4_weighted = $nilai->nilai_4 * 0.1;
            $nilai_5_weighted = $nilai->nilai_5 * 0.2;
            $nilai_6_weighted = $nilai->nilai_6 * 0.05;
            $nilai_7_weighted = $nilai->nilai_7 * 0.05;
            $nilai_8_weighted = $nilai->nilai_8 * 0.3;
            $nilai_9_weighted = $nilai->nilai_9 * 0.1;

            $komponen1 = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted;
            $komponen2 = $nilai_5_weighted + $nilai_6_weighted + $nilai_7_weighted;
            $komponen3 =  $nilai_8_weighted + $nilai_9_weighted;
            $komp32 = $komponen2 + $komponen3;
            // Calculate the total weighted nilai
            $totalNilai = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted + $nilai_5_weighted + $nilai_6_weighted;

            // Update the total_nilai property of the $nilai object
            $nilai->total_nilai = $totalNilai;

            // Tambahkan nilai pada total nilai keseluruhan
            $jumlahKomponen1 += $komponen1;
            $jumlahKomponen2 += $komponen2;
            $jumlahKomponen3 += $komponen3;
            $totalNilaiKeseluruhan += $nilai->total_nilai;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
        }     

        // Cek apakah sempro ditemukan
        if (!$sidang) {
            abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
        }


        // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro
        return view('/dosen/pen14', compact('totalNilaiKeseluruhan','totalRerataNilaiKeseluruhan','sidang','nilaiDosen','jumlahKomponen1','jumlahKomponen2','jumlahKomponen3','komponen1','komponen2','komponen3','komp32'));
    }

    public function sendDataToCoordinator()
    {
        // Ambil data sempro berdasarkan ID dosen yang sedang login
        $dosenId = auth()->user()->id;
        $semhas = Sempro::where('dospem2', $dosenId)->get();

        // Ubah nilai status_nilai menjadi "selesai dinilai" untuk setiap sempro
        foreach ($semhas as $semhas) {
            $semhas->status_nilai = 'selesai dinilai';
            $semhas->save();
        }

        // Redirect kembali ke halaman pen05Home setelah nilai berhasil diubah
        return redirect()->route('dosen.pen09Home')
            ->with('success', 'Data berhasil dikirim ke koordinator!');
    }

    public function rekapNilai($id)
    {
        {
            // Ambil data sempro berdasarkan ID
            $sempro = Sempro::find($id);
    
            // Ambil ID dosen dari kolom dospem1 pada tabel sempros
            $dospem1Id = $sempro->dospem1;
            $dospem2Id = $sempro->dospem2;
    
            // Ambil data nilai dari dosen-dosen yang terlibat dalam sempro
            $nilaiDosen = NilaiSidang::where('id_sempro', $id)->get();
    
            $totalNilaiKeseluruhan = 0;
            $totalRerataNilaiKeseluruhan = 0;
            $jumlahKomponen1 = 0;
            $jumlahKomponen2 = 0;
            $jumlahKomponen3 = 0;
    
            // Hitung total nilai dari nilai_1 sampai nilai_5 untuk setiap dosen
        foreach ($nilaiDosen as $nilai) {
            // Apply the weights to each nilai_x
            $nilai_1_weighted = $nilai->nilai_1 * 0.1;
            $nilai_2_weighted = $nilai->nilai_2 * 0.05;
            $nilai_3_weighted = $nilai->nilai_3 * 0.05;
            $nilai_4_weighted = $nilai->nilai_4 * 0.1;
            $nilai_5_weighted = $nilai->nilai_5 * 0.2;
            $nilai_6_weighted = $nilai->nilai_6 * 0.05;
            $nilai_7_weighted = $nilai->nilai_7 * 0.05;
            $nilai_8_weighted = $nilai->nilai_8 * 0.3;
            $nilai_9_weighted = $nilai->nilai_9 * 0.1;

            $komponen1 = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted;
            $komponen2 = $nilai_5_weighted + $nilai_6_weighted + $nilai_7_weighted;
            $komponen3 =  $nilai_8_weighted + $nilai_9_weighted;

            // Calculate the total weighted nilai
            $totalNilai = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted + $nilai_5_weighted + $nilai_6_weighted;

            // Update the total_nilai property of the $nilai object
            $nilai->komponen1 = $komponen1;
            $nilai->komponen2 = $komponen2;
            $nilai->komponen3 = $komponen3;

            // Tambahkan nilai pada total nilai keseluruhan
            $jumlahKomponen1 += $komponen1;
            $jumlahKomponen2 += $komponen2;
            $jumlahKomponen3 += $komponen3;
            $totalNilaiKeseluruhan += $nilai->total_nilai;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;
        }     
    
            // Cek apakah sempro ditemukan
            if (!$sempro) {
                abort(404); // Tampilkan halaman 404 jika sempro tidak ditemukan
            }
            // Tampilkan halaman "Beri Nilai" dan kirimkan data sempro dan status dosen
            return view('/koor/rekapNilaiSidang', compact('sempro', 'nilaiDosen', 'totalNilaiKeseluruhan', 'totalRerataNilaiKeseluruhan', 'dospem1Id','dospem2Id','jumlahKomponen1','jumlahKomponen2','jumlahKomponen3','komponen1','komponen2','komponen3'));
                
        }
    }

    public function exportPDF($id)
    {
        // Load file template .docx
        $templateFilePath = storage_path('app/pen14/pen14.docx');
        $templateProcessor = new TemplateProcessor($templateFilePath);

        // Ambil data sempro berdasarkan ID
        $sempro = Sempro::find($id);

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

        $totalNilaiKeseluruhan = 0;
        $totalRerataNilaiKeseluruhan = 0;

        $jumlahKomponen1 = 0;
        $jumlahKomponen2 = 0;
        $jumlahKomponen3 = 0;
        $totalKomponen32 =0;

        $rerataKom1 = 0;
        $rerataKom32 = 0;

        $C=0;
        $seventyFivePercent=0;
        $TotalCD=0;

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

            $nilai_1_weighted = $nilai->nilai_1 * 0.1;
            $nilai_2_weighted = $nilai->nilai_2 * 0.05;
            $nilai_3_weighted = $nilai->nilai_3 * 0.05;
            $nilai_4_weighted = $nilai->nilai_4 * 0.1;
            $nilai_5_weighted = $nilai->nilai_5 * 0.2;
            $nilai_6_weighted = $nilai->nilai_6 * 0.05;
            $nilai_7_weighted = $nilai->nilai_7 * 0.05;
            $nilai_8_weighted = $nilai->nilai_8 * 0.3;
            $nilai_9_weighted = $nilai->nilai_9 * 0.1;

            $komponen1 = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted;
            $komponen2 = $nilai_5_weighted + $nilai_6_weighted + $nilai_7_weighted;
            $komponen3 =  $nilai_8_weighted + $nilai_9_weighted;
            $komp32 = $komponen2 + $komponen3;
            // Calculate the total weighted nilai
            $totalNilai = $nilai_1_weighted + $nilai_2_weighted + $nilai_3_weighted + $nilai_4_weighted + $nilai_5_weighted + $nilai_6_weighted;

            // Update the total_nilai property of the $nilai object
            $nilai->komponen1 = $komponen1;
            $nilai->komponen2 = $komponen2;
            $nilai->komponen3 = $komponen3;

            // Tambahkan nilai pada total nilai keseluruhan
            $jumlahKomponen1 += $nilai->komponen1;
            $nilai->jumlahKomponen32 = $komponen2 + $komponen3;
            $totalKomponen32 += $nilai->jumlahKomponen32;

            // Tambahkan nilai pada total nilai keseluruhan
            $totalNilaiKeseluruhan += $nilai->total_nilai;
            $totalRerataNilaiKeseluruhan = $totalNilaiKeseluruhan / 5;

            $rerataKom32 = $jumlahKomponen1 / 5;
            $rerataKom1 = $totalKomponen32 / 5;

            $C=$rerataKom32+ $rerataKom1;
            $seventyFivePercent = $C * 0.75;

            $TotalCD= $seventyFivePercent + $twentyFivePercent;
            
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
            $templateProcessor->setValue("ks{$tableRowIndex}", $nilai->komponen1);
            $templateProcessor->setValue("kd{$tableRowIndex}", $nilai->jumlahKomponen32);
            $templateProcessor->setValue("kt{$tableRowIndex}", $nilai->komponen3);

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

        

        $templateProcessor->setValue("dospem1_name", $dospem1Name);
        $templateProcessor->setValue("dospem2_name", $dospem2Name);

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
