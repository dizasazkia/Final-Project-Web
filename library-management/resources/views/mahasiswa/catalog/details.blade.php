@extends('mahasiswa.templates.master')

@section('content')
<div class="flex flex-col pt-10 sm:ml-64 px-5 md:px-20 bg-white rounded-lg shadow-md" style="margin-right: 25px; margin-bottom: 25px; border-radius: 25px;">
    <!-- Container Buku -->
    <div class="display: flex; justify-content: center; gap-6">
        <div class="flex flex-col md:flex-row items-start justify-center mt-5 p-6" style="gap: 6rem;">
            <!-- Cover Buku -->
            <div class="w-full md:w-1/3 flex justify-center" style="max-width: 250px;">
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover Buku" 
                    class="rounded-lg shadow-lg object-cover" 
                    style="width: 100%; height: auto;">
            </div>            
        
            <!-- Detail Buku -->
            <div class="display: flex; justify-content: center;md:w-2/3 flex flex-col" style="min-width: 300px; max-width: 500px">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">{{ $book->judul }}</h2>
                <h4 class="text-xl text-gray-500 mb-4">By {{ $book->penulis }}</h4>
        
                <!-- Detail Informasi -->
                <div class="bg-gray-50 rounded-lg p-4 shadow-md flex-1 mb-5 mt-5">
                    <p class="text-gray-700 mb-2"><strong>Category:</strong> {{ $book->kategori }}</p>
                    <p class="text-gray-700 mb-2"><strong>Publisher:</strong> {{ $book->penerbit ?? '-' }}</p>
                    <p class="text-gray-700"><strong>Publication Date:</strong> {{ $book->tahun_terbit }}</p>
                </div>
        
                <!-- Form Borrow -->
                <form id="borrowForm" action="{{ route('borrow.store', $book->id) }}" method="POST" class="mt-5">
                    @csrf
                    <button type="submit" 
                            class="bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        Borrow Now
                    </button>
                </form>
            </div>
        </div>
        

        <!-- Description Section -->
        <div class="w-full p-6 mt-10">
            <!-- Section Title for Description -->
            <div class="flex justify-start mb-4">
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full shadow-md">
                    <h5 class="text-xl font-bold">Description</h5>
                </div>
            </div>
        
            <!-- Description Content -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                <p class="text-gray-800 text-justify">{{ $book->deskripsi }}</p>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="w-full p-6">
            <div class="flex justify-start items-center mb-4">
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full shadow-md">
                    <h5 class="text-xl font-bold">Reviews:</h5>
                </div>
            </div>
            @if ($reviews->isEmpty())
                <p class="text-gray-500">No reviews available for this book yet.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($reviews as $review)
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h6 class="font-bold text-gray-800 mb-2">
                                {{ $review->user->name }}
                                <span class="text-sm text-gray-500">({{ $review->created_at->format('d M Y') }})</span>
                            </h6>
                            <p class="text-yellow-300 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </p>
                            <p class="text-gray-700 text-sm">{{ $review->komentar }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button (with margin adjustment) -->
    <div class="mt-5  mb-5 flex justify-start">
        <a href="{{ url()->previous() }}" 
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">
            Back
        </a>
    </div>
</div>


<!-- Modal Success -->
<div id="successModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-bold">Success</h5>
            <button id="closeModal" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <p class="text-gray-700">Buku berhasil dipinjam!</p>
        <div class="mt-4 text-right">
            <button id="closeModalBtn" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Modal Error -->
<div id="errorModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-bold">Error</h5>
            <button id="closeErrorModal" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <p id="errorMessage" class="text-gray-700"></p>
        <div class="mt-4 text-right">
            <button id="closeErrorModalBtn" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400">
                Close
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#borrowForm').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#successModal').removeClass('hidden').addClass('flex');
                    } else {
                        $('#errorMessage').text(response.message || 'Terjadi kesalahan.');
                        $('#errorModal').removeClass('hidden').addClass('flex');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                    $('#errorMessage').text(errorMessage);
                    $('#errorModal').removeClass('hidden').addClass('flex');
                }
            });
        });

        $('#closeModal, #closeModalBtn, #closeErrorModal, #closeErrorModalBtn').on('click', function() {
            $('#successModal, #errorModal').removeClass('flex').addClass('hidden');
        });
    });
</script>

@endsection
