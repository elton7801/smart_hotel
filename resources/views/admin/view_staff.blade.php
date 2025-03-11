<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <style>
        /* General Styles */


        .housekeeping-title {
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #fff;
            padding: 15px;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            border-radius: 10px;
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            margin-bottom: 20px;
        }

        .container-box {
            background: #332929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
        }

        .table-container {
            overflow-x: auto;
            padding: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: rgb(154, 218, 243);
        }

        .table th, .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        .table th {
            background: #0056b3;
            color: white;
            text-transform: uppercase;
        }

        .table td input {
            width: 80%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: 0.3s;
        }

        .table td input:focus {
            border-color: #007bff;
            box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
        }

        /* Status Colors */
        .status-in-progress {
            background-color: #ffeb99 !important;
        }

        .status-done {
            background-color: #d4f8d4 !important;
        }

        /* Custom Checkbox */
        .addon-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .addon-container label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }


        /* Button Styling */
        .assign-btn {
            background: #28a745;
            color: white;
            font-size: 16px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            display: block;
            margin: 20px auto;
        }

        .assign-btn:hover {
            background: #218838;
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 14px;
                padding: 10px;
            }

            .table td input {
                width: 100%;
                padding: 6px;
            }

            .addon-container {
                gap: 3px;
            }

            .assign-btn {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')

<div class="page-content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="container-box">
            <h2 class="housekeeping-title">Housekeeping Assignation</h2>

            @if(session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <form action="{{ url('assign_housekeeping') }}" method="POST">
            @csrf

            <div class="table-container">
                <table class="table">
                    <tr>
                        <th>Staff Name</th>
                        <th>12:00</th>
                        <th>13:00</th>
                        <th>14:00</th>
                    </tr>

                    @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>

                            @foreach(['12:00', '13:00', '14:00'] as $time)
                                @php
                                    $assignment = $assignments->where('user_id', $user->id)
                                                              ->where('time_slot', $time)
                                                              ->first();
                                    $statusClass = '';
                                    if ($assignment) {
                                        if ($assignment->status == 'in progress') {
                                            $statusClass = 'status-in-progress';
                                        } elseif ($assignment->status == 'done') {
                                            $statusClass = 'status-done';
                                        }
                                    }
                                @endphp
                                <td class="{{ $statusClass }}">
                                    <input type="text"
                                           name="assignments[{{ $user->id }}][{{ $time }}][room_number]"
                                           placeholder="Room #"
                                           value="{{ $assignment ? $assignment->room_number : '' }}">

                                    <div class="addon-container">
                                        <label>
                                            <input type="checkbox"
                                                   class="custom-checkbox"
                                                   name="assignments[{{ $user->id }}][{{ $time }}][addon][]"
                                                   value="pillow"
                                                   {{ isset($assignment) && in_array('pillow', explode(',', $assignment->special_request ?? '')) ? 'checked' : '' }}>
                                            Pillow
                                        </label>
                                        <label>
                                            <input type="checkbox"
                                                   class="custom-checkbox"
                                                   name="assignments[{{ $user->id }}][{{ $time }}][addon][]"
                                                   value="blanket"
                                                   {{ isset($assignment) && in_array('blanket', explode(',', $assignment->special_request ?? '')) ? 'checked' : '' }}>
                                            Blanket
                                        </label>
                                        <label>
                                            <input type="checkbox"
                                                   class="custom-checkbox"
                                                   name="assignments[{{ $user->id }}][{{ $time }}][addon][]"
                                                   value="bed"
                                                   {{ isset($assignment) && in_array('bed', explode(',', $assignment->special_request ?? '')) ? 'checked' : '' }}>
                                            Bed
                                        </label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>

            <button type="submit" class="assign-btn">Assign</button>
            </form>
        </div>

    @include('admin.footer')
        </div>
    </div>
</div>


</body>
</html>
