@extends('layout.navbar')

@section('content')
<div class="font-lato max-w-screen-lg mx-auto">
  <div class="bg-gray-200 shadow-lg rounded-lg p-6">
    <div class="ml-5">
        <h2 class="text-xl font-bold mb-10 text-center">PENILAIAN SIDANG AKHIR</h2>
        <div class="md:flex">
        @if ($sempro->mahasiswa)
            <img class="object-cover w-full md:h-44 md:w-36 md:rounded-none border-black border-8 border-solid md:border-solid" src="{{ asset('images/' . $sempro->mahasiswa->gambar) }}" alt="{{ $sempro->nama }}"> 
        @else
            <p>Tidak ada gambar mahasiswa yang tersedia.</p>
        @endif  
            <div class ="ml-5">
            <p class="text-lg font-bold">Nama : {{ $sempro->nama }}</p>  
            <p class="text-lg">NIM : {{ $sempro->nim }}</p>
            <p class="text-lg">Jurusan : {{ $sempro->jurusan }}</p>
            <p class="text-lg font-semibold">Judul : {{ $sempro->judul }}</p>
            </div>
        </div>
 
        <form class="font-lato " action="{{ route('simpanNilaiSidang') }}" method="POST">
            @csrf
            <div class="flex flex-wrap mt-5">
            <div class="mb-6">
                <input type="hidden" name="id_sempro" value="{{ $sempro->id }}" id="id_sempro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            </div>
            <div class="mb-6">
                <input type="hidden" name="id_dosen" value="{{ Auth::user()->id }}" id="id_dosen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            </div>
            @if (Auth::user()->id === $sempro->dospem1 || Auth::user()->id === $sempro->dospem2)
            <div class ="md:w-1/2 "> 
                <p class="text-xl">PELAKSANAAN PENELITIAN</p> 
                <div class="mb-6">
                    <label for="nilai_1" class="block mb-2 text-sm font-medium text-black dark:text-white">Kedisiplinan (bobot 10%)</label>
                    <input type="number" name="nilai_1" id="nilai_1" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  placeholder="Masukkan Nilai">
                </div> 
                <div class="mb-6">
                    <label for="nilai_2" class="block mb-2 text-sm font-medium text-black dark:text-white">Ketekunan (bobot 5%)</label>
                    <input type="number" name="nilai_2" id="nilai_2" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai">
                </div> 
                <div class="mb-6">
                    <label for="nilai_3" class="block mb-2 text-sm font-medium text-black dark:text-white">Ketelitian (bobot 5%)</label>
                    <input type="number" name="nilai_3" id="nilai_3" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai">
                </div> 
                <div class="mb-6">
                    <label for="nilai_4" class="block mb-2 text-sm font-medium text-black dark:text-white">Keterampilan (bobot 10%)</label>
                    <input type="number" name="nilai_4" id="nilai_4" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai">
                </div>
            </div>
            @endif
            <div class ="md:w-1/2 ">
                <p class="text-xl">PENULISAN TA</p> 
                <div class="mb-6">
                    <label for="nilai_5" class="block mb-2 text-sm font-medium text-black dark:text-white">Isi Laporan TA (bobot 20%)</label>
                    <input type="number" name="nilai_5" id="nilai_5" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai" required>
                </div>
                <div class="mb-6">
                    <label for="nilai_6" class="block mb-2 text-sm font-medium text-black dark:text-white">Kesesuaian format (mengacu pada pedoman penulisan Tugas Akhir FMIPA) (bobot 5%)</label>
                    <input type="number" name="nilai_6" id="nilai_6" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai" required>
                </div> 
                <div class="mb-6">
                    <label for="nilai_7" class="block mb-2 text-sm font-medium text-black dark:text-white">Bahasa (mengikuti bahasa baku/EYD) (bobot 5%)</label>
                    <input type="number" name="nilai_7" id="nilai_7" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai" required>
                </div>
            </div>
            <div class ="md:w-1/2 "> 
                <p class="text-xl">UJIAN KOMPREHENSIF</p> 
                <div class="mb-6">
                    <label for="nilai_8" class="block mb-2 text-sm font-medium text-black dark:text-white">Penguasaan materi penelitian dan ilmu dasar yang telah dipelajari (bobot 30%)</label>
                    <input type="number" name="nilai_8" id="nilai_8" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai" required>
                </div> 
                <div class="mb-6">
                    <label for="nilai_9" class="block mb-2 text-sm font-medium text-black dark:text-white">Kemampuan diskusi (bobot 10%)</label>
                    <input type="number" name="nilai_9" id="nilai_9" class="bg-gray-50 w-full md:w-5/6 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukkan Nilai" required>
                </div>   
            </div>
            </div>

            <div class="block space-y-4 md:flex md:space-y-0 md:space-x-4">
                <!-- Modal toggle -->
                <button data-modal-target="small-modal" data-modal-toggle="small-modal" class="text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Approved
                </button>
            </div>
            <!-- Small Modal -->
            <div id="small-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Konfirmasi Nilai
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="small-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6">
                            <div> Komponen 1: <span id="komp1"></span></div>
                            <div> Komponen 2: <span id="komp2"></span></div>
                            <div> Komponen 3: <span id="komp3"></span></div>
                    <!-- Tampilkan total nilai di sini dengan JavaScript -->
                    
                    
                    
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button id="kirimData" data-modal-hide="small-modal" type="submit" class="text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Approved</button>
                            <button data-modal-hide="small-modal" type="button" class="text-black-500 bg-red-500 hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <button type="submit" class="text-white bg-green-500 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Approved</button> -->
        </form>
    </div>
</div>
</div>

<script>
// Add a change event listener to each input field
const inputFields = document.querySelectorAll('input');
inputFields.forEach(inputField => {
  inputField.addEventListener('change', () => {
    // Get the values of the input fields
    const nilai_1 = parseInt(document.querySelector('#nilai_1').value) || 0;
    const nilai_2 = parseInt(document.querySelector('#nilai_2').value) || 0;
    const nilai_3 = parseInt(document.querySelector('#nilai_3').value) || 0;
    const nilai_4 = parseInt(document.querySelector('#nilai_4').value) || 0;
    const nilai_5 = parseInt(document.querySelector('#nilai_5').value) || 0;
    const nilai_6 = parseInt(document.querySelector('#nilai_6').value) || 0;
    const nilai_7 = parseInt(document.querySelector('#nilai_7').value) || 0;
    const nilai_8 = parseInt(document.querySelector('#nilai_8').value) || 0;
    const nilai_9 = parseInt(document.querySelector('#nilai_9').value) || 0;

    const n1 = nilai_1 * 0.1;
    const n2 = nilai_2 * 0.05;
    const n3 = nilai_3 * 0.05;
    const n4 = nilai_4 * 0.1;
    const n5 = nilai_5 * 0.2;
    const n6 = nilai_6 * 0.05;
    const n7 = nilai_7 * 0.05;
    const n8 = nilai_8 * 0.3;
    const n9 = nilai_9 * 0.1;

    const komp1 = n1 + n2 + n3 + n4;
    const komp2 = n5 + n6 + n7;
    const komp3 = n8 + n9;

    document.querySelector('#komp1').textContent = komp1;
    document.querySelector('#komp2').textContent = komp2;
    document.querySelector('#komp3').textContent = komp3;
  });
});
</script>
@endsection