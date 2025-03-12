<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Wave animation */
        .wave-loader {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .wave {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            margin: 0 5px;
            animation: waveAnimation 1.5s infinite ease-in-out;
        }

        .wave:nth-child(1) {
            animation-delay: 0s;
        }

        .wave:nth-child(2) {
            animation-delay: 0.2s;
        }

        .wave:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes waveAnimation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.5);
            }
            100% {
                transform: scale(1);
            }
        }

        .loading-message {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        .wave-container {
            width: 100%;
}

        .wave-room {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 15px;
            border: 1px solid #242222;
            border-radius: 5px;
            background-color: #292525;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .wave-room-number {
            font-size: 16px;
            font-weight: bold;
            flex: 2; /* Takes more space */
            text-align: left;
        }

        .wave {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            animation: waveAnimation 1.5s infinite ease-in-out;
            flex-shrink: 0;
        }

        .wave-status {
            font-size: 16px;
            color: #555;
            flex: 1;
            text-align: right;
        }

        /* Responsive fix */
    @media (max-width: 768px) {
        .wave-room {
            flex-direction: column;
            text-align: center;
        }

        .wave-room-number,
        .wave-status {
            text-align: center;
            width: 100%;
            margin-bottom: 5px;
        }
    }

    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Dashboard</h2>
        </div>

        <section class="no-padding-top no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-user-1"></i><strong>Total Clients</strong></div>
                                <div class="number dashtext-1">{{ $totalUsers }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-contract"></i><strong>Total Rooms</strong></div>
                                <div class="number dashtext-2">{{ $totalRooms }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-paper-and-pencil"></i><strong>Total Bookings</strong></div>
                                <div class="number dashtext-3">{{ $totalBookings }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-writing-whiteboard"></i><strong>Total Income (MYR)</strong></div>
                                <div class="number dashtext-4">{{ number_format($totalRevenue) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <!-- Line Chart -->
                    <div class="col-lg-6">
                        <div class="line-chart block">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <!-- Bar Chart -->
                    <div class="col-lg-6">
                        <div class="bar-chart block">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="no-padding-top no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-user-1"></i><strong>Total Message Receive</strong></div>
                                <div class="number dashtext-1">{{ $msg }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-contract"></i><strong>Total Rooms still in cleaning</strong></div>
                                <div class="number dashtext-2">{{ $housekeeping }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title"><i class="icon-paper-and-pencil"></i><strong>Total Rooms that have been cleaned</strong></div>
                                <div class="number dashtext-3">{{ $housekeepingDone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <i class="icon-paper-and-pencil"></i>
                                    <strong>Average Rating</strong>
                                </div>
                                <div class="number dashtext-3">
                                    <strong>{{ number_format($averageRating, 1) }}</strong>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star text-warning"></i> {{-- Full Star --}}
                                        @elseif ($i - $averageRating < 1)
                                            <i class="fas fa-star-half-alt text-warning"></i> {{-- Half Star --}}
                                        @else
                                            <i class="far fa-star text-warning"></i> {{-- Empty Star --}}
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="rooms-still-cleaning block">
                            <h3>Rooms Still in Cleaning</h3>
                            <div id="loading" class="loading-message">
                                @if($housekeepingRoom->isEmpty())
                                    <p>No rooms are currently being cleaned.</p>
                                @else
                                    <ul class="wave-container">
                                        <!-- Loop through the room numbers in progress -->
                                        @foreach($housekeepingRoom as $room)
                                            <li class="wave-room">
                                                <div class="wave-room-number">Room Number: {{ $room }}</div>
                                                <div class="wave"></div>
                                                <div class="wave-status">In Progress</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div id="cleaningRoomsList" style="display: none;">
                                <!-- Dynamically populated list of rooms -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chart.js Scripts -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var ctxLine = document.getElementById('lineChart').getContext('2d');
                var ctxBar = document.getElementById('barChart').getContext('2d');

                // Monthly Bookings Chart Data
                var labels = @json($allMonths->pluck('month'));
                var bookingData = @json($allMonths->pluck('total_bookings'));
                var incomeData = @json($allMonths->pluck('total_income'));


                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Bookings',
                                data: bookingData,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Total Income (MYR)',
                                data: incomeData,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // ✅ Corrected Room Bookings Bar Chart Data
                var roomLabels = @json($roomTitles ?? []);
                var roomBookingCounts = @json($roomBookingCounts ?? []);
                console.log("Room Labels:", roomLabels);
                console.log("Room Booking Counts:", roomBookingCounts);



                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: roomLabels,
                        datasets: [{
                            label: 'Number of Bookings',
                            data: roomBookingCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true },
                            y: {
                                    beginAtZero: true,
                                    suggestedMin: 0, // ✅ Helps ensure the chart starts at zero
                                    suggestedMax: Math.max(...roomBookingCounts) + 1 // Ensures bars are visible properly
                                } // ✅ Ensure Y-axis starts at 0
                        }
                    }
                });
            });


        </script>

    @include('admin.footer')
</body>
</html>
