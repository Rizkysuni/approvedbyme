@extends('layout.navbar')

@section('content')
<div class="font-lato max-w-3xl mx-auto bg-gray-200">
  <div class=" shadow-lg rounded-lg p-6 ">
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
        <input placeholder="Masukkan Jam Seminar" type="text" name="jam" id="jam" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>
    <div class="mb-6">
        <input type="text" name="dospem1_name" id="dospem1_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Dosen Pembimbing 1" autocomplete="off">
        <input type="hidden" name="dospem1" id="dospem1">
        <div id="dospem1Suggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
    </div>

    <div class="mb-6">
        <input type="text" name="dospem2_name" id="dospem2_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Dosen Pembimbing 2" autocomplete="off">
        <input type="hidden" name="dospem2" id="dospem2">
        <div id="dospem2Suggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
    </div>

    <div class="mb-6">
        <input type="text" name="penguji1_name" id="penguji1_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Penguji 1" autocomplete="off">
        <input type="hidden" name="penguji1" id="penguji1">
        <div id="penguji1Suggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
    </div>

    <div class="mb-6">
        <input type="text" name="penguji2_name" id="penguji2_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Penguji 2" autocomplete="off">
        <input type="hidden" name="penguji2" id="penguji2">
        <div id="penguji2Suggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
    </div>

    <div class="mb-6">
        <input type="text" name="penguji3_name" id="penguji3_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Penguji 3" autocomplete="off">
        <input type="hidden" name="penguji3" id="penguji3">
        <div id="penguji3Suggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
    </div>

    

    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-8 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
</div>
</div>

<script>
    // Mendapatkan daftar dosens dari PHP ke JavaScript
    let dosens = @json($dosens);

    // Fungsi umum untuk menampilkan rekomendasi dan mengelola input
    function setupAutoComplete(inputId, inputNameId, suggestionsId) {
        const inputName = document.getElementById(inputNameId);
        const inputIdField = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);

        inputName.addEventListener('input', function () {
            let keyword = this.value.toLowerCase();

            // Mengosongkan hasil rekomendasi sebelumnya
            suggestions.innerHTML = '';

            // Menampilkan hasil rekomendasi yang cocok dengan kata kunci
            dosens.forEach(function (dosen) {
                if (dosen.name.toLowerCase().includes(keyword)) {
                    // Buat elemen div untuk menampilkan hasil rekomendasi
                    let suggestionItem = document.createElement('div');
                    suggestionItem.textContent = dosen.name;
                    suggestionItem.classList.add('suggestion-item');

                    // Tambahkan event listener untuk menangani klik pada hasil rekomendasi
                    suggestionItem.addEventListener('click', function () {
                        // Mengisi input dengan nama dosen yang dipilih
                        inputName.value = dosen.name;
                        // Menyimpan ID dosen yang sesuai
                        inputIdField.value = dosen.id;
                        // Sembunyikan hasil rekomendasi
                        suggestions.innerHTML = '';
                    });

                    // Tambahkan hasil rekomendasi ke div hasil rekomendasi
                    suggestions.appendChild(suggestionItem);
                }
            });
        });
    }

    // Menjalankan fungsi setupAutoComplete untuk masing-masing field
    setupAutoComplete('dospem1', 'dospem1_name', 'dospem1Suggestions');
    setupAutoComplete('dospem2', 'dospem2_name', 'dospem2Suggestions');
    setupAutoComplete('penguji1', 'penguji1_name', 'penguji1Suggestions');
    setupAutoComplete('penguji2', 'penguji2_name', 'penguji2Suggestions');
    setupAutoComplete('penguji3', 'penguji3_name', 'penguji3Suggestions');
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#jam", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true // Format 24 jam
    });
</script>


@endsection