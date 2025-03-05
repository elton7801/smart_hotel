<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <style type="text/css">
        .table_deg {
            border: 2px solid white;
            margin: auto;
            width: 80%;
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

        input, button {
            padding: 5px;
            margin: 5px;
        }

        .assign-btn {
            background-color: green;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
        }

        .assign-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2>Housekeeping Assignation</h2>

                <form action="{{ url('assign_housekeeping') }}" method="POST">
                    @csrf
                    <table class="table_deg">
                        <tr>
                            <th class="th_deg">Staff Name</th>
                            <th class="th_deg">12:00</th>
                            <th class="th_deg">13:00</th>
                            <th class="th_deg">14:00</th>
                            <th class="th_deg">Room Assignation</th>
                        </tr>

                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>

                                @foreach(['12:00', '13:00', '14:00'] as $time)
                                    @php
                                        // Find assignment for the staff at the specific time
                                        $assignment = $assignments->where('user_id', $user->id)
                                                                  ->where('time_slot', $time)
                                                                  ->first();
                                        // Determine background color based on status
                                        $bgColor = '';
                                        if ($assignment) {
                                            if ($assignment->status == 'in progress') {
                                                $bgColor = 'background-color: yellow;';
                                            } elseif ($assignment->status == 'done') {
                                                $bgColor = 'background-color: lightgreen;';
                                            }
                                        }
                                    @endphp
                                    <td>
                                        <input type="text"
                                               name="assignments[{{ $user->id }}][{{ $time }}]"
                                               placeholder="Enter Room Number"
                                               value="{{ $assignment ? $assignment->room_number : '' }}"
                                               style="{{ $bgColor }}">
                                    </td>
                                @endforeach

                                <td>
                                    <button type="submit" class="assign-btn">Save</button>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </form>
            </div>
        </div>
    </div>

    @include('admin.footer')
    <script src="//code.tidio.co/2zz9vpjtxroyrgjo1yprppzulcongdjp.js" async></script>
</body>
</html>
