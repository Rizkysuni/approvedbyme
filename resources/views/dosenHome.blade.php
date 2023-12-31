@extends('layout.navbar')

@section('content')
    <div class="font-lato text-3xl">
    <p>Selamat Datang, {{ auth()->user()->name }}</p>
    </div>

    <br>
    <div class="font-lato relative overflow-x-auto">
    <table id="tabel-data" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                No
                </th>
                <th scope="col" class="px-6 py-3">
                   Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Jurusan
                </th>
                <th scope="col" class="px-6 py-3">
                    Ruangan
                </th>
                <th scope="col" class="px-6 py-3">
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-3">
                    Jam
                </th>
                <th scope="col" class="px-6 py-3">
                    Seminar
                </th>
                <th scope="col" class="px-6 py-3">
                    Aksi 
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach ($sempros as $index => $sempro)
        @if ($sempro->seminar === 'Seminar Proposal')
            @if (App\Models\NilaiSempro::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                @php
                    $timeDifference = now()->diffInHours($sempro->semproDateTime);
                @endphp
                @if ($timeDifference < 4)
                <tr  onclick="window.location.href='{{ route('beriNilai', ['id' => $sempro->id]) }}'" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @else
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @endif
            @else
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            @endif

        @elseif ($sempro->seminar === 'Seminar Hasil')
            @if (App\Models\NilaiSemhas::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                @php
                    $timeDifference = now()->diffInHours($sempro->semproDateTime);
                @endphp
                @if ($timeDifference < 4)
                <tr  onclick="window.location.href='{{ route('beriNilaiSemhas', ['id' => $sempro->id]) }}'" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @else
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @endif
            @else
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">       
            @endif
        
        @elseif ($sempro->seminar === 'Sidang Akhir')
            @if (App\Models\NilaiSidang::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                @php
                    $timeDifference = now()->diffInHours($sempro->semproDateTime);
                @endphp
                @if ($timeDifference < 4)
                <tr  onclick="window.location.href='{{ route('beriNilaiSidang', ['id' => $sempro->id]) }}'" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @else
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                @endif
            @else
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            @endif
        @endif
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $index + 1 }}
                </th>
                <td class="px-6 py-4">
                {{ $sempro->nama }}
                </td>
                <td class="px-6 py-4">
                {{ $sempro->jurusan }}
                </td>
                <td class="px-6 py-4">
                {{ $sempro->ruangan}}
                </td>
                <td class="px-6 py-4">
                    {{ $sempro->tglSempro}}
                </td>
                <td class="px-6 py-4">
                    {{ $sempro->jam}}
                </td>
                <td class="px-6 py-4">
                    {{ $sempro->seminar}}
                </td>
                <td>
                    @if ($sempro->seminar === 'Seminar Proposal')
                        @if (App\Models\NilaiSempro::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
                            <p class="text-green-500">Sudah Dinilai</p>
                        @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                            @php
                                $timeDifference = now()->diffInHours($sempro->semproDateTime);
                            @endphp
                            @if ($timeDifference < 4)
                                <a href="{{ route('beriNilai', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Beri Nilai</a>
                            @else
                                <p class="text-red-500">Waktu telah berakhir</p>
                            @endif
                        @else
                        <p class="text-red-500">Belum Waktunya</p>
                    @endif
                    @elseif ($sempro->seminar === 'Seminar Hasil')
                        @if (App\Models\NilaiSemhas::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
                            <p class="text-green-500">Sudah Dinilai</p>
                        @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                            @php
                                $timeDifference = now()->diffInHours($sempro->semproDateTime);
                            @endphp
                            @if ($timeDifference < 4)
                                <a href="{{ route('beriNilaiSemhas', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Beri Nilai</a>
                            @else
                                <p class="text-red-500">Waktu telah berakhir</p>
                            @endif
                        @else
                            <p class="text-red-500">Belum Waktunya</p>
                        @endif
                    @elseif ($sempro->seminar === 'Sidang Akhir')
                        @if (App\Models\NilaiSidang::where('id_dosen', Auth::user()->id)->where('id_sempro', $sempro->id)->exists())
                            <p class="text-green-500">Sudah Dinilai</p>
                        @elseif ($currentTime->greaterThanOrEqualTo($sempro->semproDateTime))
                            @php
                                $timeDifference = now()->diffInHours($sempro->semproDateTime);
                            @endphp
                            @if ($timeDifference < 4)
                                <a href="{{ route('beriNilaiSidang', ['id' => $sempro->id]) }}" class="text-blue-500 md:hover:text-blue-700">Beri Nilai</a>
                            @else
                                <p class="text-red-500">Waktu telah berakhir</p>
                            @endif
                        @else
                            <p class="text-red-500">Belum Waktunya</p>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
</div>

<script>
        $(document).ready(function(){
            $('#tabel-data').DataTable({
            responsive: true
        });
            
        });
    </script>

@endsection