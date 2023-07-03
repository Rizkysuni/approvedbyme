@extends('layout.navbar')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-black shadow-lg rounded-lg p-6 ">
    <h2 class="text-xl font-semibold mb-4">Form Title</h2>   
<form action="{{ route('simpanNilai') }}" method="POST">
    @csrf
    <div class="mb-6">
        <input type="hidden" name="id_sempro" value="{{ $sempro->id }}" id="id_sempro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div>
    <div class="mb-6">
        <input type="hidden" name="id_dosen" value="{{ Auth::user()->id }}" id="id_dosen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div>  
    <div class="mb-6">
        <label for="nilai_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nilai_1</label>
        <input type="text" name="nilai_1" id="nilai_1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  required>
    </div> 
    <div class="mb-6">
        <label for="nilai_2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nilai_2</label>
        <input type="text" name="nilai_2" id="nilai_2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div> 
    <div class="mb-6">
        <label for="nilai_3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nilai_3</label>
        <input type="text" name="nilai_3" id="nilai_3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Judul" required>
    </div> 
    <div class="mb-6">
        <label for="nilai_4" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nilai_4</label>
        <input type="text" name="nilai_4" id="nilai_4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
    </div> 
    <div class="mb-6">
        <label for="nilai_5" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nilai_5</label>
        <input type="text" name="nilai_5" id="nilai_5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Ruangan" required>
    </div>   
    
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</div>
</div>
@endsection