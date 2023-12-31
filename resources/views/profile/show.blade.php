@extends('layout.navbar')

@section('content')
    <div class=" font-lato container"> 

    <div class="border-b-2 block md:flex">

        <div class="w-full md:w-2/5 p-4 sm:p-6 lg:p-8 bg-white shadow-md">

            @if(auth()->user()->role === 'mahasiswa' || auth()->user()->role === 'dosen' || auth()->user()->role === 'koordinator')
            <div class="flex justify-between">
                <span class="text-xl font-semibold block">User Profile</span>
            </div>
            @if (session('successfoto'))
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{session('successfoto')}}</span>
                </div>
            @endif

            <div class="w-full p-8 mx-2 flex justify-center ">
                <img id="showImage" class="max-w-xs w-1/2 items-center border" src="{{ asset('images/' . $user->gambar) }}" alt="">                          
            </div>

            <form action="{{ route('editFoto',$user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group flex">
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Ubah</button>
                </div>
                @error('gambar')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
                </form>
            @endif

            

            @if(auth()->user()->role === 'dosen' || auth()->user()->role === 'koordinator')
                        @if ($signature)
                        @if (session('successttd'))
                            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Berhasil!</strong>
                                <span class="block sm:inline">{{session('successttd')}}</span>
                            </div>
                        @endif

                            <div class="w-full p-8 mx-2 flex justify-center ">
                                <img  class="object-cover w-full  mt-20 md:h-32 md:w-48  border-gray-400 border-4 border-solid rounded-md" src="{{ asset('images/' . $signature->signature_path) }}" alt="Tanda Tangan">                        
                            </div>
                            <p class="py-3"> Ubah Tanda Tangan <p>
                            <form action="{{ route('editTtd',$user->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group flex">
                                    <input type="file" class="form-control" id="ttd" name="ttd" accept="image/*" required>
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Ubah</button>
                                </div>
                                @error('ttd')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </form>
                            @else
                            <p>Tanda tangan belum ditambahkan.</p>
                            <div class="container mt-36">
                                <form action="{{ route('saveSignature') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label for="signature">Tambahkan Tanda Tangan (Image)</label>
                                    <div class="form-group flex">
                                        <input type="file" class="form-control" id="signature" name="signature" accept="image/*" required>
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
        </div>

        

        <div class="w-full md:w-3/5 p-8 bg-white lg:ml-4 shadow-md">
            <div class="rounded  shadow p-6">
                <div class="pb-6">       
                    <label for="about" class="font-semibold text-gray-700 block pb-1">Email</label>
                    <input disabled id="email" class="border-1  rounded-r px-4 py-2 w-full" type="email" value="{{ $user->name }}" />
                </div>
                <div class="pb-6">       
                    <label for="about" class="font-semibold text-gray-700 block pb-1">NIM</label>
                    <input disabled id="email" class="border-1  rounded-r px-4 py-2 w-full" type="email" value="{{ $user->nim }}" />
                </div>
                <div class="pb-6">       
                    <label for="about" class="font-semibold text-gray-700 block pb-1">Email</label>
                    <input disabled id="email" class="border-1  rounded-r px-4 py-2 w-full" type="email" value="{{ $user->email }}" />
                </div>
                <div class="pb-6">       
                    <label for="about" class="font-semibold text-gray-700 block pb-1">Jurusan</label>
                    <input disabled id="email" class="border-1  rounded-r px-4 py-2 w-full" type="email" value="{{ $user->jurusan }}" />
                </div>
            </div>
        </div>

    </div>





        <!-- <div class="flex flex-col items-center  border border-gray-200 rounded-lg shadow md:flex-row md:w-10/12 md:h-fit dark:border-gray-700 dark:bg-gray-800 ">
            <div class="flex flex-col justify-between ml-16 p-4 leading-normal">
                @if (session('success'))
                    <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{session('success')}}</span>
                    </div>
                @endif
                <p>Profile</p>@if(auth()->user()->role === 'mahasiswa')
                       
                            <div class="container mt-1.5 bg-gray-600 rounded-lg  p-4 leading-normal">
                                <h1>Ubah Foto Profile</h1>
                                <form action="{{ route('editFoto',$user->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="gambar">Update Foto</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Simpan</button>
                                </form>
                            </div>
                        
                    @endif
            </div> 
            <div class="flex flex-col justify-between bg-gray-600 rounded-lg ml-52 p-4 leading-normal">
                    <p>Nama: {{ $user->name }}</p>
                    <p>Nim: {{ $user->nim }}</p>
                    <p>Email: {{ $user->email }}</p>
                    <p>jurusan: {{ $user->jurusan}}</p>
                    @if(auth()->user()->role === 'dosen' || auth()->user()->role === 'koordinator')
                        @if ($signature)
                            <img  class="object-cover w-full  mt-5 md:h-32 md:w-48 md:rounded-none border-black border-4 border-solid md:border-solid" src="{{ asset('images/' . $signature->signature_path) }}" alt="Tanda Tangan">
                        @else
                            <p>Tanda tangan belum ditambahkan.</p>
                            <div class="container">
                                <h1>Tambahkan Tanda Tangan</h1>
                                <form action="{{ route('saveSignature') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="signature">Tanda Tangan (Image)</label>
                                        <input type="file" class="form-control" id="signature" name="signature" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            
        </div> -->
    </div>

@endsection