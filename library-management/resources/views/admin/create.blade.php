@extends('admin.templates.master')

@section('content')

<div class="p-4 sm:ml-64 relative overflow-x-auto shadow-md sm:rounded-lg bg-white" style="border-radius: 25px; margin-right: 25px; margin-bottom: 25px; min-height: 100vh;">
    <form class="max-w-2xl mx-auto mt-14 p-6 shadow-lg bg-gray-50 rounded-xl" action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" style="border-radius: 25px; margin-bottom: 25px;">
        @csrf

        <!-- Input untuk Cover Image -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="file" name="cover_image" id="cover_image" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" onchange="previewImage(event)" />
            <label for="cover_image" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Cover
            </label>
            @error('cover_image')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Preview Gambar -->
        <div class="relative z-0 w-full mb-5 group">
            <img id="imagePreview" src="#" alt="Image Preview" class="max-w-xs max-h-40 object-contain mb-3 hidden" />
        </div>

        <!-- Input Judul, Penulis, Penerbit, Tahun Terbit -->
        @foreach(['judul', 'penulis', 'penerbit', 'tahun_terbit'] as $field)
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="{{ $field }}" id="{{ $field }}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="{{ old($field) }}" required />
                <label for="{{ $field }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                </label>
                @error($field)
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <!-- Dropdown Kategori -->
        <div class="relative z-0 w-full mb-5 group">
            <select name="kategori" id="kategori" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <option value="Fiction" {{ old('kategori') == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                <option value="Fantasy" {{ old('kategori') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                <option value="Romance" {{ old('kategori') == 'Romance' ? 'selected' : '' }}>Romance</option>
                <option value="Horror" {{ old('kategori') == 'Horror' ? 'selected' : '' }}>Horror</option>
                <option value="Thriller" {{ old('kategori') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
            </select>  
            <label for="kategori" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Kategori
            </label>
            @error('kategori')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input untuk Stok -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="stok" id="stok" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="{{ old('stok') }}" required />
            <label for="stok" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Stok
            </label>
            @error('stok')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input untuk Deskripsi -->
        <div class="relative z-0 w-full mb-5 group">
            <textarea name="deskripsi" id="deskripsi" rows="4" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required>{{ old('deskripsi') }}</textarea>
            <label for="deskripsi" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Deskripsi
            </label>
            @error('deskripsi')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
        </button>
    </form>
</div>

<!-- Preview Image -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
