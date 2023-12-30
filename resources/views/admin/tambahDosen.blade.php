@extends('layout.navbar')

@section('content')

<div class="font-lato max-w-3xl mx-auto bg-gray-200">
  <div class=" shadow-lg rounded-lg p-6 ">
    <h2 class="text-xl font-semibold mb-4">Tambah Data Dosen</h2>   
    <form action="{{ route('storeDosen') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-6">
        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama:</label>
            <input type="text" name="nama" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nama" required>
        </div> 
        
        @if ($errors->has('nim'))
            <div class="text-red-500">{{ $errors->first('nim') }}</div>
        @endif
        <div class="mb-6">
        <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nip:</label>
            <input type="text" name="nim" id="nim"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nip" required>
        </div> 
        @if ($errors->has('email'))
            <div class="text-red-500">{{ $errors->first('email') }}</div>
        @endif
        <div class="mb-6">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email:</label>
            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Email" required>
        </div> 
        @if ($errors->has('password'))
            <div class="text-red-500">{{ $errors->first('password') }}</div>
        @endif
        <div class="mb-6">
        <label for="Password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password:</label>
            <input type="password" name="password" id="password"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required>
        </div> 
        

        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required>
        </div>
        <div class="mb-6">
        <label for="kelamin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin:</label>
            <select id="kelamin" name="kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="mb-6">
        <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan:</label>
            <input type="text" name="jabatan" id="jabatan"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Jabatan" required>
        </div>   
        <div class="mb-6">
        <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pendidikan:</label>
            <input type="text" name="pendidikan" id="pendidikan"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Pendidikan" required>
        </div>  
        <div class="mb-6">
        <label for="golongan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Golongan:</label>
            <input type="text" name="golongan" id="golongan"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Golongan" required>
        </div>  
        
        <div class="mb-6">            
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="gambar">Foto Profil:</label>
            <input name="gambar" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="gambar" type="file" required>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-8 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
</div>
</div>

@endsection