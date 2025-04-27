<div class="gallery">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage text-center">
                    <h2 class="fw-bold text-uppercase">Gallery</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($gallary as $index => $gallary)
                <div class="col-md-3 col-sm-6">
                    <div class="gallery_img">
                        <figure class="position-relative">
                            <img src="/gallary/{{ $gallary->image }}" alt="#" class="img-thumbnail rounded shadow-sm"
                                 data-bs-toggle="modal" data-bs-target="#imageModal"
                                 onclick="openModal({{ $index }})" style="cursor:pointer; transition: transform 0.3s;">
                        </figure>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-0">
            <div class="modal-body text-center position-relative">
                <!-- Left Arrow -->
                <button class="btn btn-dark rounded-circle shadow position-absolute start-0 top-50 translate-middle-y gallery-btn"
                        onclick="prevImage()">
                    &#10094;
                </button>

                <!-- Image -->
                <img id="modalImage" src="" alt="#" class="img-fluid rounded shadow-lg"
                     style="max-height: 80vh; transition: 0.3s ease-in-out;">

                <!-- Fullscreen Button Below Image -->
                <div class="mt-3">
                    <button class="btn btn-light gallery-fullscreen" onclick="toggleFullScreen()" title="Toggle Fullscreen">
                        🔍 Fullscreen
                    </button>
                </div>

                <!-- Right Arrow -->
                <button class="btn btn-dark rounded-circle shadow position-absolute end-0 top-50 translate-middle-y gallery-btn"
                        onclick="nextImage()">
                    &#10095;
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Required for Modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Hover Effect for Image */
    .gallery_img img:hover {
        transform: scale(1.05);
    }

    /* Gallery Buttons (Arrows & Fullscreen) */
    .gallery-btn {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 1.5rem;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        border: none;
    }

    .gallery-btn:hover {
        background: rgba(255, 255, 255, 0.8);
        color: black;
    }

    /* Fullscreen Button */
    .gallery-fullscreen {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
        transition: 0.3s ease-in-out;
    }

    .gallery-fullscreen:hover {
        background: white;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.8);
    }


    .btn-close:hover {
        transform: scale(1.2);
    }
</style>

<script>
    let images = @json($gallary->pluck('image'));
    let currentIndex = 0;

    function openModal(index) {
        currentIndex = index;
        document.getElementById('modalImage').src = "/gallary/" + images[currentIndex];
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }

    function closeModal() {
        let modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
        if (modal) modal.hide();
    }

    function prevImage() {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
        document.getElementById('modalImage').src = "/gallary/" + images[currentIndex];
    }

    function nextImage() {
        currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
        document.getElementById('modalImage').src = "/gallary/" + images[currentIndex];
    }

    function toggleFullScreen() {
        let modalImage = document.getElementById('modalImage');
        if (!document.fullscreenElement) {
            modalImage.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }
</script>
