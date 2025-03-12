<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <style>
        .table_deg {
            border: 2px solid white;
            margin: auto;
            width: 100%;
            text-align: center;
            margin-top: 40px;
        }
        .th_deg {
            background-color: skyblue;
            padding: 15px;
        }
        td {
            padding: 10px;
        }
        .filter-form {
            margin: 20px auto;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">

                <!-- 🔹 Date Filter Form -->
                <form class="filter-form" method="GET" action="{{ url('bookings') }}">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" >
                    <input type="date" name="end_date" value="{{ request('end_date') }}" >
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ url('bookings') }}" class="btn btn-secondary">Reset</a>
                </form>

                <table class="table_deg">
                    <tr>
                        <th class="th_deg">Customer Name</th>
                        <th class="th_deg">Email</th>
                        <th class="th_deg">Phone</th>
                        <th class="th_deg">Arrival Date</th>
                        <th class="th_deg">Leaving Date</th>
                        <th class="th_deg">Status</th>
                        <th class="th_deg">Payment</th>
                        <th class="th_deg">Room Title</th>
                        <th class="th_deg">Special Request</th>
                        <th class="th_deg">Room Number</th>
                        <th class="th_deg">Image</th>
                        <th class="th_deg">Status Update</th>
                    </tr>

                    @foreach($data as $booking)
                    <tr>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->start_date }}</td>
                        <td>{{ $booking->end_date }}</td>
                        <td>
                            @if($booking->status == 'ready') <span style="color:green">Room Ready</span> @endif
                            @if($booking->status == 'cancel') <span style="color:red">Booking Cancelled</span> @endif
                            @if($booking->status == 'checkout') <span style="color:grey">Check Out</span> @endif
                            @if($booking->status == 'waiting') <span style="color:yellow">Waiting</span> @endif
                        </td>
                        <td>{{ $booking->payment_status }}</td>
                        <td>{{ $booking->room->room_title }}</td>
                        <td>{{ $booking->special_request }}</td>
                        <td>{{ $booking->room_number }}</td>
                        <td><img style="width: 200px" src="/room/{{ $booking->room->image }}"></td>
                        <td>
                            <a class="btn btn-success" href="{{ url('approve', $booking->id) }}">Ready</a>
                            <a class="btn btn-warning" href="{{ url('reject_book', $booking->id) }}">Cancel</a>
                            <a onclick="return confirm('Confirm Delete?');" class="btn btn-danger" href="{{ url('delete_booking', $booking->id) }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <!-- 🔹 Pagination -->
                <div class="pagination-container">
                    {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>

    @include('admin.footer')
</body>
</html>
