@extends('layout.navbar')

@section('content')

<div class="w-full p-6 center bg-gray-700 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
        <p class="text-5xl">Detail Rekapitulasi Penilaian Sidang AKhir</p>
        <p class="text-4xl">{{ $sidang->nama }}</p>
        <p class="text-3xl">{{ $sidang->nim }}</p>
        <p class="text-2xl">{{ $sidang->judul }}</p>
        <p class="text-2xl">{{ $sidang->jurusan}}</p>
        <p class="text-2xl">{{ $sidang->ruangan }}</p>
        <p class="text-2xl">Berdasarkan nilai rata-rata dari tim penguji, maka mahasiswa tersebut dinyatakan:</p>
        <div class=" p-4 rounded-lg mb-6">
            @if ($totalRerataNilaiKeseluruhan > 69)
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg">Lulus</button>
            @else
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Tidak Lulus</button>
            @endif

            <p class="text-lg font-semibold text-white dark:text-white mt-2">
                Dengan kategori nilai huruf : 
                @if ($totalRerataNilaiKeseluruhan > 88)
                    A
                @elseif ($totalRerataNilaiKeseluruhan > 77)
                    AB
                @elseif ($totalRerataNilaiKeseluruhan > 68)
                    B
                @else
                    BC
                @endif
            </p>
        </div>

        <div class="flex items-left  mx-auto relative   rounded-lg overflow-hidden">
      <table class=" text-sm text-left text-gray-500 ">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3 w-1/3">Nama Dosen</th>
                <th scope="col" class="px-6 py-3">Komponen 1</th>
                <th scope="col" class="px-6 py-3">Komponen 2</th>
                <th scope="col" class="px-6 py-3">Komponen 3</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilaiDosen as $index => $nilai)
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 w-1/3">
                        @if ($nilai->dosen)
                            {{ $nilai->dosen->name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $komponen1[$index] }}</td>
                    <td class="px-6 py-4">
                    {{ $komponen2[$index] }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komponen3[$index] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                
                <td class="px-6 py-4">Jumlah:</td>
                <td class="px-6 py-4">{{ $jumlahKomponen1 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen2 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen3 }}</td>
            </tr>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Komponen 2 + 3</td>
                <td class="px-6 py-4">{{ $komp32 }}</td>
            </tr>
        </tfoot>
    </table>
    </div>

    <div class="flex justify-end mt-6">
        <!-- Tombol Kirim -->
        <form action="{{ route('sendDataSidang') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Kirim
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
</div>

  <!-- Tombol Unduh PDF -->
  <a href="{{ route('export.pdf', ['id' => $sidang->id]) }}" class="btn btn-primary">Unduh PDF</a>

  
 
@endsection