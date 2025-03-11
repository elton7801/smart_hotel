<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        .status-in-progress {
            background-color: white;
            font-weight: bold;
        }

        .status-done {
            background-color: lightgreen;
            font-weight: bold;
        }

        .done-btn {
            background-color: green;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .done-btn:hover {
            background-color: darkgreen;
        }

        .completed {
            color: green;
            font-weight: bold;
        }

        .no-assignments {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #777;
        }

        /* Align login/register section to top-right */
        .auth-container {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .auth-container a {
            text-decoration: none;
            margin-right: 10px;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

    <div class="container">

        <!-- Authentication Links (Top Right) -->
        <div class="auth-container">
            @if (Route::has('login'))
                @auth
                    <x-app-layout></x-app-layout>
                @else
                    <a class="btn btn-success" href="{{ url('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a class="btn btn-primary" href="{{ url('register') }}">Register</a>
                    @endif
                @endauth
            @endif
        </div>

        <h2>🧹 My Assigned Rooms to Clean</h2>

        <div class="table-container">
            <table>
                <tr>
                    <th>Room Number</th>
                    <th>Time Slot</th>
                    <th>Add on</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                @foreach($assignments as $assignment)
                    <tr class="{{ $assignment->status == 'done' ? 'status-done' : 'status-in-progress' }}">
                        <td>{{ $assignment->room_number }}</td>
                        <td>{{ $assignment->time_slot }}</td>
                        <td>{{ $assignment->special_request }}</td>
                        <td>{{ ucfirst($assignment->status) }}</td>
                        <td>
                            @if($assignment->status == 'in progress')
                                <form action="{{ url('doneClean/' . $assignment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="done-btn">✅ Mark as Done</button>
                                </form>
                            @else
                                <span class="completed">✔ Completed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if($assignments->isEmpty())
                    <tr>
                        <td colspan="4" class="no-assignments">No rooms assigned to you yet.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

</body>
</html>
