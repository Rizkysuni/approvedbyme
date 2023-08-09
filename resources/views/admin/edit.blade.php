@extends('layout.navbar')

@section('content')

<div class="font-lato max-w-md mx-auto">
  <div class="bg-slate-800 shadow-lg rounded-lg p-6 ">
    <h2 class="text-xl font-semibold mb-4">Edit Data Sempro</h2>   
<form action="{{ route('sempro.update',$sempro->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-6">
    <label for="nama" class=" text-base text-white">Nama Mahasiswa</label>
        <input type="text" name="nama" id="nama" value="{{ $sempro->nama }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  required>
    </div> 
    <div class="mb-6">
    <label for="nim" class=" text-base text-white">NIM</label>
        <input type="text" name="nim" id="nim" value="{{ $sempro->nim }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div> 
    <div class="mb-6">
    <label for="judul" class="block text-base text-white">Judul Tugas Akhir</label>
        <input type="text" name="judul" id="judul" value="{{ $sempro->judul }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Judul" required>
    </div> 
    <div class="mb-6">
    <label for="jurusan" class="block text-base text-white">Jurusan</label>
        <input type="text" name="jurusan" id="jurusan" value="{{ $sempro->jurusan }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div> 
    <div class="mb-6">
    <label for="ruangan" class="block text-base text-white">Ruang Seminar</label>
        <input type="text" name="ruangan" id="ruangan" value="{{ $sempro->ruangan }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Ruangan" required>
    </div>
    <div class="mb-6">
    <label for="tglSempro" class="block text-base text-white">Tanggal Seminar</label>
        <input type="date" name="tglSempro" id="tglSempro" value="{{ $sempro->tglSempro }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Tanggal" required>
    </div>
    <div class="mb-6">
            <label for="dospem1" class="block text-base text-white">Dosen Pembimbing 1</label>
            <select name="dospem1" id="dospem1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Dosen Pembimbing 1</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->id }}" {{ $sempro->dospem1 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                @endforeach
            </select>
        </div>
    <div class="mb-6">
        <label for="dospem2" class="block text-base text-white">Dosen Pembimbing 2</label>
        <select name="dospem2" id="dospem2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Pembimbing 2</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" {{ $sempro->dospem2 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
    <label for="penguji1" class="block text-base text-white">Dosen Penguji 1</label>
        <select name="penguji1" id="penguji1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 1</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" {{ $sempro->penguji1 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
    <label for="penguji2" class="block text-base text-white">Dosen Penguji 2</label>
        <select name="penguji2" id="penguji2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 2</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" {{ $sempro->penguji2 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
    <label for="penguji3" class="block text-base text-white">Dosen Penguji 3</label>
        <select name="penguji3" id="penguji3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 3</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" {{ $sempro->penguji3 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
    <label for="seminar" class="block text-base text-white">Seminar</label>
        <select name="seminar" id="seminar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            
            <option value="seminar proposal" {{ $sempro->seminar == 'seminar proposal' ? 'selected' : '' }}>Seminar Proposal</option>
            <option value="Seminar Hasil" {{ $sempro->seminar == 'Seminar Hasil' ? 'selected' : '' }}>Seminar Hasil</option>
            <option value="Sidang Akhir" {{ $sempro->seminar == 'Sidang Akhir' ? 'selected' : '' }}>Sidang Akhir</option>
        </select>
    </div>
    <div class="mb-6">
    <label for="status_nilai" class="block text-base text-white">Status Nilai</label>
        <select name="status_nilai" id="status_nilai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            <option value="belum dinilai" {{ $sempro->status_nilai == 'belum dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
            <option value="selesai dinilai" {{ $sempro->status_nilai == 'selesai dinilai' ? 'selected' : '' }}>Selesai Dinilai</option>
        </select>
    </div> 
    
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</div>
</div>

@endsection