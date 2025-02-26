<!DOCTYPE html>
<html lang="en">
   <head>
      @include('home.css')
      <style type="text/css">
         .booking_title {
            font-size: 40px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            padding: 20px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
            margin-top: 30px;
         }

         .table_deg {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         }

         .th_deg, .td_deg {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
         }

         .th_deg {
            background-color: #ff7e5f;
            color: white;
            font-weight: bold;
         }

         tr:nth-child(even) {
            background-color: #f9f9f9;
         }

         img {
            border-radius: 8px;
         }
      </style>
   </head>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
   crossorigin="anonymous">

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
   integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
   crossorigin="anonymous"></script>

   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="#"/></div>
      </div>
      <!-- end loader -->

      <!-- header -->
      <header>
         @include('home.header')
      </header>

      <div class="page-content">
         <div class="page-header">
            <div class="container-fluid text-center">
               <h1 class="booking_title">My Booking</h1>

               <table class="table_deg">
                  <tr>
                     <th class="th_deg">Customer Name</th>
                     <th class="th_deg">Email</th>
                     <th class="th_deg">Phone</th>
                     <th class="th_deg">Arrival Date</th>
                     <th class="th_deg">Leaving Date</th>
                     <th class="th_deg">Room Status</th>
                     <th class="th_deg">Action</th>
                  </tr>

                  @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->user->email }}</td>
                        <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date }}</td>
                        <td>{{ $booking->end_date }}</td>
                        <td>{{ $booking->status }}</td>

                        <td>
                            <a class="btn btn-warning" href="{{ url('view_booking',$booking->id)}}">Check In</a>
                        </td>
                    </tr>
                    @endforeach


               </table>

            </div>
         </div>
      </div>

      @include('home.footer')
   </body>
</html>
