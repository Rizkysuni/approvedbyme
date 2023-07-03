<?php

namespace App\Http\Controllers\Api;

//import Model "Post"
use App\Models\Sempro;
use App\Models\User;
use App\Models\NilaiSempro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//import Resource
use App\Http\Resources\Resource;

//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

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
