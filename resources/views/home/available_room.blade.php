<!DOCTYPE html>
<html lang="en">
   <head>
    @include('home.css')
   </head>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
   crossorigin="anonymous">

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
   integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
   crossorigin="anonymous"></script>
   <!-- body -->
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
      <div class="our_room">
        <h1>Room available</h1>
        <div class="container">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-warning">{{ session('message') }}</div>
                @endif

                @foreach($availableRooms as $room)
                    <div class="col-md-4 col-sm-6">
                        <div class="room">
                            <div class="room_img">
                                <figure><img style="height:200px; width:350px" src="room/{{ $room->image }}" alt="#"/></figure>
                            </div>
                            <div class="bed_room">
                                <h3>{{ $room->room_title }}</h3>
                                <p style="padding:10px">{!! Str::limit($room->description, 100) !!}</p>
                                <a class="btn btn-primary" href="{{ url('room_details', $room->id) }}">Room Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

      @include('home.footer')
      <script src="//code.tidio.co/2zz9vpjtxroyrgjo1yprppzulcongdjp.js" async></script>

   </body>
</html>
