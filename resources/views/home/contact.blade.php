<style>
    .rating-label {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 8px;
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 80px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s ease-in-out, transform 0.2s;
    }

    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f39c12;
        transform: scale(1.2);
    }
</style>
<div class="contact">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="titlepage">
                <h2>Contact Us</h2>
             </div>



          </div>
       </div>
       <div class="row">
          <div class="col-md-6">
            <form id="request" class="main_form" action="{{ url('contact') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <input class="contactus" placeholder="Name" type="text" name="name" required>
                    </div>
                    <div class="col-md-12">
                        <input class="contactus" placeholder="Email" type="email" name="email" required>
                    </div>
                    <div class="col-md-12">
                        <input class="contactus" placeholder="Phone Number" type="number" name="phone" required>
                    </div>
                    <div class="col-md-12">
                        <textarea class="textarea" placeholder="Message" name="message" required>Write a review to us</textarea>
                    </div>

                    <!-- Star Rating Section -->
                    <div class="col-md-12">
                        <label for="rating" class="rating-label">⭐ Rate Us:</label>
                        <div class="star-rating">
                            <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="send_btn">Send</button>
                    </div>
                </div>
            </form>
          </div>
        <div class="col-md-6">
             <div class="map_main">
                <div class="map-responsive">
                    <iframe
                        src="https://maps.google.com/maps?q=Universiti%20Tunku%20Abdul%20Rahman,%20Sungai%20Long&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="600" height="400" frameborder="0" style="border:0; width: 100%;" allowfullscreen>
                    </iframe>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
