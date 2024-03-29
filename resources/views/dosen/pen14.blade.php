@extends('layout.navbar')

@section('content')

<div class="font-lato w-full p-6 center bg-gray-200 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col ml-3 pb-10">
    <p class=" text-2xl md:text-4xl text-center mb-10">Detail Rekapitulasi Penilaian Sidang Akhir</p>
    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm mt-5">
            <div class="grid grid-cols-1  md:grid-cols-3 text-lg">
                <div class="block p-4 bg-white text-gray-600">
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Nama :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $sempro->nama }} </p>
                    </div>
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">NIM :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $sempro->nim }} </p>
                    </div>
                      
                </div>
                <div class="block p-4 bg-white text-gray-600">
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Jurusan :</p>
                        <p class="font-bold  dark:text-gray-400">{{ $sempro->jurusan}} </p>
                    </div> 
                    <div class="mb-4">
                        <p class="font-normal  dark:text-gray-400">Ruangan :</p>
                        <p class="font-bold  dark:text-gray-400"> {{ $sempro->ruangan }}</p>
                    </div>   
                </div>
                <div class="block p-4 bg-white   text-gray-600">
                    <p class="font-normal  dark:text-gray-400">Judul :</p>
                    <p class="font-bold  dark:text-gray-400">{{ $sempro->judul }} </p>
                </div>
                
            </div>
        </div> 

        <p class="text-2xl text-center mt-10">Nilai Standar Deviasi</p>
        <div class=" p-4 rounded-lg mb-6 content-center mx-auto">
            {{$akarRatakdrt}}
        </div>
    

        <div class="items-left  mx-auto relative   rounded-lg overflow-hidden mt-10">
      <table class=" text-sm text-left text-gray-500 ">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3 w-1/3">Nama Dosen</th>
                <th scope="col" class="px-6 py-3">Status Penguji</th>
                <th scope="col" class="px-6 py-3">Komponen 1</th>
                <th scope="col" class="px-6 py-3">Komponen 2</th>
                <th scope="col" class="px-6 py-3">Komponen 3</th>
                <th scope="col" class="px-6 py-3">Total Komp 2 + Komp 3</th>
                <th scope="col" class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">1</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaDospem1 }}
                    </td>
                    <td class="px-6 py-4">
                    Sekretaris 
                    </td>
                    <td class="px-6 py-4">{{ $komp1dp1 ?? 'Nilai belum diinput' }}</td>
                    <td class="px-6 py-4">
                    {{ $komp2dp1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3dp1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $tot1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    @if($tot1) <!-- Replace $dataExists with your actual condition -->
                        <!-- Tombol Hapus -->
                        <form action="{{ route('sidang.hapusNilai', ['id' => $id1]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2">
                                <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @else
                    
                    @endif
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">2</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaDospem2 }}
                    </td>
                    <td class="px-6 py-4">
                    Ketua
                    </td>
                    <td class="px-6 py-4">{{ $komp1dp2 ?? 'Nilai belum diinput' }}</td>
                    <td class="px-6 py-4">
                    {{ $komp2dp2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3dp2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $tot2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    @if($tot2) <!-- Replace $dataExists with your actual condition -->
                        <!-- Tombol Hapus -->
                        <form action="{{ route('sidang.hapusNilai', ['id' => $id2]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2">
                                <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @else
                    
                    @endif
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">3</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji1 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg1 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $tot3?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    @if($tot3) <!-- Replace $dataExists with your actual condition -->
                        <!-- Tombol Hapus -->
                        <form action="{{ route('sidang.hapusNilai', ['id' => $id3]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2">
                                <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @else
                    
                    @endif
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">4</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji2 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg2 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $tot4 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    @if($tot4) <!-- Replace $dataExists with your actual condition -->
                        <!-- Tombol Hapus -->
                        <form action="{{ route('sidang.hapusNilai', ['id' => $id4]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2">
                                <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @else
                    
                    @endif
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                    <td scope="row" class="px-6 py-4 font-medium text-black-900 whitespace-nowrap">5</td>
                    <td class="px-6 py-4 w-1/3">
                        {{ $namaPenguji3 }}
                    </td>
                    <td class="px-6 py-4">
                    Anggota
                    </td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4">
                    {{ $komp2pg3 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $komp3pg3 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    {{ $tot5 ?? 'Nilai belum diinput' }}
                    </td>
                    <td class="px-6 py-4">
                    @if($tot5) <!-- Replace $dataExists with your actual condition -->
                        <!-- Tombol Hapus -->
                        <form action="{{ route('sidang.hapusNilai', ['id' => $id5]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2">
                                <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                            </button>
                        </form>
                    @else
                    
                    @endif
                    </td>
                </tr>
        </tbody>
        <tfoot>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Jumlah:</td>
                <td class="px-6 py-4">{{ $jumlahKomponen1 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen2 }}</td>
                <td class="px-6 py-4">{{ $jumlahKomponen3 }}</td>
            </tr>
            <tr class="bg-white border-b dark:bg-white-800 dark:border-black-700">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">Komponen 2 + 3</td>
                <td class="px-6 py-4">{{ $jumlahKomponen3 + $jumlahKomponen2 }}</td>
            </tr>
        </tfoot>
    </table>

    @if (in_array(auth()->user()->role, ['admin']))
    <div class="py-3 px-3">
        <button class="bg-green-600 text-white px-10 py-2 rounded-lg">
        <!-- Tombol Unduh PDF -->
        <a href="{{ route('export.pdfSidang', ['id' => $sempro->id]) }}" class="btn btn-primary">Unduh PDF</a>
        </button>
    </div>    
    @endif

    </div>

    @if (auth()->user()->id === $sempro->dospem2 || auth()->user()->id === $sempro->dospem1)
    <div class="flex justify-end mt-6 mx-auto">
    @if (($tot1 > 0 ? 1 : 0) + ($tot2 > 0 ? 1 : 0) + ($tot3 > 0 ? 1 : 0) + ($tot4 > 0 ? 1 : 0) + ($tot5 > 0 ? 1 : 0) >= 4 && $akarRatakdrt < 5)
        <!-- Tombol Kirim -->
        <form action="{{ route('sendDataSidang') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                Kirim
            </button>
        </form>
        @else 
        <!-- Tombol Kirim -->
        <form action="#" method="POST">
            @csrf
            <button type="submit" class="bg-gray-500 text-white font-bold py-2 px-4 rounded mt-4" disabled>
                Kirim
            </button>
        </form>
       @endif
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
</div>


  
 
@endsection