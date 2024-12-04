@extends('templates.master')

@section('content')
<div class="catalog" style="background-color: rgb(250, 250, 250); background-repeat: repeat; min-height: 100vh;">
    <div class="row align-items-center ms-5 me-5 p-5">
        <!-- Cover Buku -->
        <div class="col-md-4 mt-5 d-flex justify-content-center">
            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                class="img-fluid rounded shadow-lg" 
                alt="Cover Buku" 
                style="width:280px; height: auto;">
        </div>
        <!-- Detail Buku -->
        <div class="col-md-5">
            <h2 class="fw-bold mb-3 mt-5" style="color: #3d426c;">{{ $book->judul }}</h1>
            <h4 class="text-muted mb-4">By {{ $book->penulis }}</h4>
        
            <!-- Wrapper untuk kategori, publisher, dan publication date -->
            <div class="p-3 rounded mb-5 shadow-sm" style="background-color: #f1f4fa; color: #3d426c;">
                <p class="mb-2"><strong>Category:</strong> {{ $book->kategori }}</p>
                <p class="mb-2"><strong>Publisher:</strong> {{ $book->penerbit ?? '-' }}</p>
                <p class="mb-0"><strong>Publication Date:</strong> {{ $book->tahun_terbit }}</p>
            </div>
        
            <!-- Form untuk peminjaman buku -->
            <a href="{{ route('login') }}" class="btn btn-dark" style="background-color: #676060;">Borrow Now</a>
        </div>        
    </div>

    <!-- Deskripsi -->
    <div class=" p-4" style="margin-left: 70px; margin-right: 70px;">
        <!-- Wrapper untuk judul -->
        <div class="d-flex justify-content-center align-items-center p-2 shadow-sm rounded-pill mb-3" 
            style="background-color: #d1dffb; max-width: 150px; height: 50px;">
            <h5 class="fw-bold mb-0" style="color: #3d426c;">Description</h5>
        </div>
        <!-- Wrapper untuk deskripsi -->
        <div class="p-3 shadow-sm" style="background-color: #e9f1fe; border-radius: 25px;">
            <p>{{ $book->deskripsi }}</p>
        </div>
    </div>
    
    <!-- Semua Review -->
    <div class="reviews  p-4" style="margin-left: 70px; margin-right: 70px;">
        <div class="p-3 rounded-pill mb-3" 
            style="background-color: ; max-width: 150px; height: 50px;">
            <h5 class="fw-bold mb-0" style="color: #3d426c;">Reviews :</h5>
        </div>
        @if ($reviews->isEmpty())
            <p>No reviews available for this book yet.</p>
        @else
            <div class="row">
                @foreach ($reviews as $review)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm" style="border: none;">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <strong>{{ $review->user->name }}</strong>
                                    <span class="text-muted" style="font-size: 12px;">({{ $review->created_at->format('d M Y') }})</span>
                                </h6>
                                <p class="card-text mb-2">
                                    {{-- <strong>Rating:</strong>  --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </p>
                                <p class="card-text" style="font-size: 14px;">{{ $review->komentar }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3 mb-5" style="margin-left: 95px;">Back</a>
</div>

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Buku berhasil dipinjam!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
                    if(response.success) {
                        $('#successModal').modal('show');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memproses peminjaman buku.');
                }
            });
        });
    });
</script>
@endsection
