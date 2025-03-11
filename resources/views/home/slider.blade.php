<section class="banner_main position-relative">
    @if(session()->has('message'))
             <div class="alert alert-success">
                <button type="button" class="btn-close" data-bs-dismiss='alert'></button>
                {{ session()->get('message') }}
             </div>
             @endif
    <div id="myCarousel" class="carousel slide banner" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="first-slide w-100" src="images/banner1.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="second-slide w-100" src="images/banner2.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="third-slide w-100" src="images/banner3.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Booking Form Overlay -->
    <div class="booking_form_container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="book_room p-4 rounded shadow">
                        <h2 class="text-center text-white mb-4">Book a Room Online</h2>
                        <form action="{{ url('search_availability') }}" method="get">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-bold text-white">Check-in Date:</label>
                                    <input type="date" name="check_in" id="check_in" class="form-control rounded-pill" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold text-white">Check-out Date:</label>
                                    <input type="date" name="check_out" id="check_out" class="form-control rounded-pill" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="fw-bold text-white">Number of Guests (Pax):</label>
                                    <select name="pax" id="pax" class="form-control rounded-pill pax-dropdown" required>
                                        <option value="" disabled selected>Select Guests</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Person' : 'People' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-light w-100 px-3 py-2 rounded-pill shadow-sm">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript to prevent past dates for check-in & ensure valid check-out selection -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let checkInInput = document.getElementById("check_in");
        let checkOutInput = document.getElementById("check_out");

        // Prevent past dates for check-in
        checkInInput.min = new Date().toISOString().split("T")[0];

        checkInInput.addEventListener("change", function () {
            let checkInDate = new Date(this.value);
            let minCheckOutDate = new Date(checkInDate);
            minCheckOutDate.setDate(minCheckOutDate.getDate() + 1); // Next day

            checkOutInput.min = minCheckOutDate.toISOString().split("T")[0];
        });
    });
</script>

<!-- CSS for Overlay -->
<style>
    /* Position the form over the carousel */
    .booking_form_container {
        position: absolute;
        top: 50%;
        left: 30%; /* Shifted left (50% - 20%) */
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 1000px;
        z-index: 10;
    }

    #pax {
        height: 50px; /* Match the height of the input fields */
        padding: 10px; /* Ensure consistent padding */
        font-size: 16px; /* Maintain readability */
        width: 90%;
        border-radius: 24px;
    }

    .book_room {
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 25px;
        text-align: center;
        color: white;
    }

    .form-control {
        border: 2px solid #ddd;
        padding: 10px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
    }

    button {
        font-size: 18px;
        font-weight: bold;
    }

    /* Ensure carousel image is always visible */
    .carousel-inner img {
        height: 90vh;
        object-fit: cover;
    }
</style>
