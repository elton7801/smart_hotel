<!DOCTYPE html>
<html lang="en">
   <head>
      @include('home.css')
      <style type="text/css">
         body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
         }

         .booking_container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
         }

         .booking_title {
            font-size: 36px;
            font-weight: bold;
            color: #ff7e5f;
            margin-bottom: 20px;
         }

         .info_row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
         }

         .info_row label {
            font-weight: bold;
            color: #333;
            width: 40%;
            text-align: left;
         }

         .info_row span {
            width: 60%;
            text-align: left;
            color: #555;
         }

         .image_container {
            margin-top: 20px;
            text-align: center;
         }

         .image_container img {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto;
        }

         @media (max-width: 600px) {
            .info_row {
               flex-direction: column;
               text-align: left;
            }

            .info_row label,
            .info_row span {
               width: 100%;
            }
         }
      </style>
   </head>

   <body>
      <!-- loader -->
      <div class="loader_bg">
        <div class="loader"><img src="{{ asset('images/loading.gif') }}" alt="Loading..."/></div>
      </div>

      <!-- header -->
      <header>
         @include('home.header')
      </header>

      <!-- Booking Content -->
      <div class="booking_container">
         <h1 class="booking_title">My Booking</h1>

         <div class="info_row">
            <label>Room Title:</label>
            <span>{{ $data->room->room_title }}</span>
         </div>

         <div class="info_row">
            <label>Name:</label>
            <span>{{ $data->name }}</span>
         </div>

         <div class="info_row">
            <label>Email:</label>
            <span>{{ $data->email }}</span>
         </div>

         <div class="info_row">
            <label>Phone:</label>
            <span>{{ $data->phone }}</span>
         </div>

         <div class="info_row">
            <label>Room Price:</label>
            <span>RM {{ $data->room->price }}</span>
         </div>

         <div class="info_row">
            <label>Arrival Date:</label>
            <span>{{ $data->start_date }}</span>
         </div>

         <div class="info_row">
            <label>Leaving Date:</label>
            <span>{{ $data->end_date }}</span>
         </div>

         @if (!empty($data->special_request))
            <div class="info_row">
                <label>Selected Add-ons:</label>
                <span>{{ $data->special_request }}</span>
            </div>
        @endif

         <div class="image_container">
            <label>Room Image:</label>
            <img src="/room/{{ $data->room->image }}" alt="Room Image">
         </div>
         <td>
            <a style="margin-top: 20px;" onclick="return confirm ('confirm Delete ?')" class="btn btn-danger" href="{{ url('cancel_booking',$data->id)}}">Cancel Booking</a>
            <a style="margin-top: 20px;" class="btn btn-success" href="{{ url('stripe', $data->id) }}">Proceed To Payment</a>
        </td>
      </div>


      @include('home.footer')
      <script src="//code.tidio.co/2zz9vpjtxroyrgjo1yprppzulcongdjp.js" async></script>
   </body>
</html>
