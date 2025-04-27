<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')
    <style type="text/css">
        label {
            display: inline-block;
            width: 200px;
        }

        .div_deg {
            padding-top: 30px;
        }
        .booking_title{
            font-size: 40px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(135deg, #5fa4ff, #7ba4fe);
            padding: 20px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
            margin-top:30px;
        }
    </style>
  </head>
  <body>
    @include('admin.header')

    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">

                <div>
                    <h1 class="booking_title">Approve This Booking</h1>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-top: 20px;">
                            <strong>Whoops!</strong> There were some problems with your input:
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('approve_book', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="div_deg">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $data->name }}" required>
                        </div>

                        <!-- Email -->
                        <div class="div_deg">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $data->email }}" required>
                        </div>

                        <!-- Phone -->
                        <div class="div_deg">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ $data->phone }}" required>
                        </div>

                        <!-- Status (Read-only) -->
                        <div class="div_deg">
                            <label>Status</label>
                            <input type="text" name="status" value="{{ $data->status }}" readonly>
                        </div>

                        <!-- Payment Status (Read-only) -->
                        <div class="div_deg">
                            <label>Payment Status</label>
                            <input type="text" name="payment_status" value="{{ $data->payment_status }}" readonly>
                        </div>

                        <!-- Start Date (Read-only) -->
                        <div class="div_deg">
                            <label>Start Date</label>
                            <input type="date" name="start_date" value="{{ $data->start_date }}" readonly>
                        </div>

                        <!-- End Date (Read-only) -->
                        <div class="div_deg">
                            <label>End Date</label>
                            <input type="date" name="end_date" value="{{ $data->end_date }}" readonly>
                        </div>
                        <div class="div_deg">
                        <label>Room Number:</label>
                            <input name="qrcode_content" rows="4" cols="50" placeholder="Room number#" value="{{ $data->room_number }}"/>
                        </div>
                        <!-- Submit Button -->
                        <div class="div_deg">
                            <input class="btn btn-primary" type="submit" value="Approve Booking">
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    @include('admin.footer')
  </body>
</html>
