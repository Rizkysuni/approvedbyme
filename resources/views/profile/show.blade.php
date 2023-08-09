@extends('layout.navbar')

@section('content')

    <div class=" font-lato container">     

        <div class="flex flex-col items-center bg-gray-800 border border-gray-200 rounded-lg shadow md:flex-row md:w-10/12 md:h-96 dark:border-gray-700 dark:bg-gray-800 ">
            <div class="flex flex-col justify-between ml-16 p-4 leading-normal">
                <p>Profile</p>
                <img class="object-cover w-full  mt-5 md:h-56 md:w-48 md:rounded-none border-black border-8 border-solid md:border-solid" src="{{ asset('images/' . $user->gambar) }}" alt="">
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
            
        </div>
    </div>

@endsection