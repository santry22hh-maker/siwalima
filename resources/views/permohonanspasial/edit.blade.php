 <x-jiglayout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Edit Permohonan Data') }}
         </h2>
     </x-slot>

     <div class="py-8">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                 <div class="p-6 sm:p-8 bg-white border-b border-gray-200">

                     @if ($errors->any())
                         <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative"
                             role="alert">
                             <strong class="font-bold">Terjadi Kesalahan!</strong>
                             <ul class="mt-2 list-disc list-inside text-sm">
                                 @foreach ($errors->all() as $error)
                                     <li>{{ $error }}</li>
                                 @endforeach
                             </ul>
                         </div>
                     @endif

                     <form action="{{ route('permohonanspasial.update', $permohonan->id) }}" method="POST"
                         enctype="multipart/form-data">
                         @csrf
                         @method('PUT')

                         <div class="space-y-8">
                             {{-- BAGIAN DATA PEMOHON --}}
                             <div>
                                 <h3 class="font-bold text-xl text-gray-800 border-b pb-2 mb-4">Data Pemohon</h3>
                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                     {{-- Nama Pemohon --}}
                                     <div>
                                         <label for="nama_pemohon" class="block text-sm font-medium text-gray-700">Nama
                                             Pemohon</label>
                                         <x-text-input id="nama_pemohon" class="block mt-1 w-full" type="text"
                                             name="nama_pemohon" :value="old('nama_pemohon', $permohonan->nama_pemohon)" required autofocus />
                                     </div>
                                     {{-- NIP --}}
                                     <div>
                                         <label for="nip"
                                             class="block text-sm font-medium text-gray-700">NIP</label>
                                         <x-text-input id="nip" class="block mt-1 w-full" type="text"
                                             name="nip" :value="old('nip', $permohonan->nip)" required />
                                     </div>
                                     {{-- Jabatan --}}
                                     <div>
                                         <label for="jabatan"
                                             class="block text-sm font-medium text-gray-700">Jabatan</label>
                                         <x-text-input id="jabatan" class="block mt-1 w-full" type="text"
                                             name="jabatan" :value="old('jabatan', $permohonan->jabatan)" required />
                                     </div>
                                     {{-- Instansi --}}
                                     <div>
                                         <label for="instansi"
                                             class="block text-sm font-medium text-gray-700">Instansi</label>
                                         <x-text-input id="instansi" class="block mt-1 w-full" type="text"
                                             name="instansi" :value="old('instansi', $permohonan->instansi)" required />
                                     </div>
                                     {{-- Email --}}
                                     <div>
                                         <label for="email"
                                             class="block text-sm font-medium text-gray-700">E-Mail</label>
                                         <x-text-input id="email" class="block mt-1 w-full" type="email"
                                             name="email" :value="old('email', $permohonan->email)" required />
                                     </div>
                                     {{-- Nomor HP --}}
                                     <div>
                                         <label for="no_hp" class="block text-sm font-medium text-gray-700">No.
                                             Telepon / Ext</label>
                                         <x-text-input id="no_hp" class="block mt-1 w-full" type="text"
                                             name="no_hp" :value="old('no_hp', $permohonan->no_hp)" required />
                                     </div>
                                 </div>
                             </div>

                             {{-- BAGIAN DATA SURAT & PERMINTAAN IGT (DINAMIS) --}}
                             <div x-data="{ items: {{ json_encode($detailItems->map->only(['jenis_data', 'cakupan'])) }} }">
                                 <h3 class="font-bold text-xl text-gray-800 border-b pb-2 mb-4">Data Surat & Permintaan
                                     IGT</h3>

                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                     <div>
                                         <label for="nomor_surat" class="block text-sm font-medium text-gray-700">Nomor
                                             Surat Pemohon</label>
                                         <x-text-input id="nomor_surat" class="block mt-1 w-full" type="text"
                                             name="nomor_surat" :value="old('nomor_surat', $permohonan->nomor_surat)" required />
                                     </div>
                                     <div>
                                         <label for="tanggal_surat"
                                             class="block text-sm font-medium text-gray-700">Tanggal Surat
                                             Pemohon</label>
                                         <x-text-input id="tanggal_surat" class="block mt-1 w-full" type="date"
                                             name="tanggal_surat" :value="old('tanggal_surat', $permohonan->tanggal_surat)" required />
                                     </div>
                                 </div>

                                 <div class="mt-6">
                                     <h4 class="font-semibold text-lg text-gray-700 mb-2">Daftar Data IGT yang Diminta
                                     </h4>
                                     <div class="space-y-4">
                                         <template x-for="(item, index) in items" :key="index">
                                             <div class="flex items-end space-x-4 p-4 border rounded-lg bg-gray-50">
                                                 <div class="flex-grow">
                                                     <label class="block text-sm font-medium text-gray-700">Jenis
                                                         Data</label>
                                                     <select :name="`requested_data[${index}][jenis_data]`"
                                                         x-model="item.jenis_data" required
                                                         class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                         <option value="">-- Pilih Jenis Data --</option>
                                                         @foreach ($jenisDataOptions as $option)
                                                             <option value="{{ $option }}">{{ $option }}
                                                             </option>
                                                         @endforeach
                                                     </select>
                                                 </div>
                                                 <div class="flex-grow">
                                                     <label
                                                         class="block text-sm font-medium text-gray-700">Cakupan</label>
                                                     <select :name="`requested_data[${index}][cakupan]`"
                                                         x-model="item.cakupan" required
                                                         class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                         <option value="">-- Pilih Cakupan --</option>
                                                         <option value="Provinsi">Provinsi</option>
                                                         <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                                     </select>
                                                 </div>
                                                 <div>
                                                     <button type="button" @click="items.splice(index, 1)"
                                                         x-show="items.length > 0"
                                                         class="px-3 py-2 text-sm bg-red-500 text-white rounded-md hover:bg-red-600"
                                                         title="Hapus Baris">-</button>
                                                 </div>
                                             </div>
                                         </template>
                                     </div>
                                     <button type="button" @click="items.push({ jenis_data: '', cakupan: '' })"
                                         class="mt-4 px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                         + Tambah Permintaan Data
                                     </button>
                                 </div>
                             </div>
                         </div>

                         {{-- Tombol Aksi --}}
                         <div class="flex items-center justify-end mt-8 pt-4 border-t">
                             <a href="{{ route('permohonanspasial.index') }}"
                                 class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                             <button type="submit"
                                 class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                 Update Permohonan
                             </button>
                         </div>
                     </form>

                 </div>
             </div>
         </div>
     </div>
     </x-layout-jig>
