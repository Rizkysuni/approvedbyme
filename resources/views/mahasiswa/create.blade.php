@extends('layout.navbar')

@section('content')
<div class="font-lato max-w-md mx-auto">
  <div class="bg-slate-800 shadow-lg rounded-lg p-6 ">
    <h2 class="text-xl font-semibold mb-4">Silahkan Isi Data Seminar Proposal Anda</h2>   
<form action="{{ route('seminar.store') }}" method="POST">
    @csrf
    <div class="mb-6">
        <input type="hidden" name="id_mahasiswa" value="{{ Auth::user()->id }}" id="id_mahasiswa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Id" required>
    </div> 
    <div class="mb-6">
        <input type="text" name="nama" id="nama" value="{{ Auth::user()->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  readonly>
    </div> 
    <div class="mb-6">
        <input type="text" name="nim" id="nim" value="{{ Auth::user()->nim }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
    </div> 
    <div class="mb-6">
        <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Judul" required>
    </div> 
    <div class="mb-6">
        <input type="text" name="jurusan" id="jurusan" value="{{ Auth::user()->jurusan }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
    </div> 
    <div class="mb-6">
        <input type="text" name="ruangan" id="ruangan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Ruangan" required>
    </div>
    <div class="mb-6">
        <input type="date" name="tglSempro" id="tglSempro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Tanggal" required>
    </div> 
    <div class="mb-6">
        <select name="dospem1" id="dospem1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Pembimbing 1</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
        <select name="dospem2" id="dospem2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Pembimbing 2</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
        <select name="penguji1" id="penguji1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 1</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
        <select name="penguji2" id="penguji2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 2</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="mb-6">
        <select name="penguji3" id="penguji3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        <option value="">Dosen Penguji 3</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
        @endforeach
        </select>
    </div>
    
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</div>
</div>

@endsection