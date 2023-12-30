@extends('layout.navbar')

@section('content')
<div class="font-lato text-3xl flex items-center justify-between">
  
</div>
    <br>
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <span class="font-medium">Sukses!</span>{{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto ">
        <div class="flex">
            <a href="{{ route('addDosen') }}" class="mr-3 text-white mb-3 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800">
                Tambah Dosen
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>

            <!-- Modal toggle -->
            <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="text-white mb-3 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover-bg-blue-700 dark:focus:ring-blue-800" type="button">
            Ganti Koordinator
            </button>

        </div>
    <div class="items-center">
        
        <h4 class="text-2xl font-bold text-center dark:text-white">Daftar Dosen</h4>

    </div>

    <table id="tabel-data" class="font-lato w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                  <th scope="col" class="px-6 py-3">
                      No
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Nama
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Nip
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Email
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Jenis Kelamin
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Jabatan
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Golongan
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Peran
                  </th>
                  <th scope="col" class="px-6 py-3">
                      aksi
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach ($users as $index => $user)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4  font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ $index + 1 }}
                  </th>
                  <td class="px-6 py-4 whitespace-normal">
                  {{ $user->name }}
                  </td>
                  <td class="px-6 py-4">
                  {{ $user->nim}}
                  </td>
                  <td class="px-6 py-4 whitespace-normal">
                  {{ $user->email}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $user->Kelamin}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $user->Jabatan}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $user->Golongan}}
                  </td>
                  <td class="px-6 py-4">
                  {{ $user->role}}
                  </td>
                  <td class="px-6 py-4">
                    <!-- Tombol Edit -->
                    <!-- <a href="{{ route('sempro.edit', ['id' => $user->id]) }}" class="mr-2">
                        <ion-icon style="font-size: 24px;" name="pencil-sharp"></ion-icon>
                    </a> -->

                    <!-- Tombol Hapus -->
                    <form action="{{ route('dosen.destroy', ['id' => $user->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ml-2">
                            <ion-icon style="font-size: 24px;" name="trash-outline"></ion-icon>
                        </button>
                    </form>


                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
      
  </div>

 <!-- Main modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative max-h-96  bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Ubah Koordinator</h3>
                <form class="space-y-6" action="{{ route('updateKoor') }}" method="POST">
                @csrf
                    <div class="mb-6">
                        <input type="text" name="dosen_name" id="dosen_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Dosen" autocomplete="off">
                        <input type="hidden" name="dosen" id="dosen">
                        <div id="dosenSuggestions" class="mt-2 pl-3 bg-white border max-h-40 overflow-y-auto z-50 w-full cursor-pointer"></div>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                   
                </form>
            </div>
        </div>
    </div>
</div>

  
  <script>
        $(document).ready(function(){
            $('#tabel-data').DataTable({
            responsive: true
        });
            
        });
    </script>
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
    setupAutoComplete('dosen', 'dosen_name', 'dosenSuggestions');
</script>
@endsection