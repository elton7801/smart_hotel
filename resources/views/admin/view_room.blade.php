<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')
    <style type="text/css">
    .table_deg
    {
        border:2px solid white;
        margin:auto;
        width:80%;
        text-align:center;
        margin-top: 40px;
    }
    .th_deg
    {
        background-color: skyblue;
        padding:15px;
    }

    tr
    {
        border=3px solid white;
    }

    td
    {
        padding:10px;
    }
    </style>
  </head>
  <body>
    @include('admin.header')

    @include('admin.sidebar')

    <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <table class="table_deg">
                <tr>
                    <th class="th_deg">Room Title</th>
                    <th class="th_deg">Description</th>
                    <th class="th_deg">Price</th>
                    <th class="th_deg">Room quantity</th>
                    <th class="th_deg">Wifi</th>
                    <th class="th_deg">Room Type</th>
                    <th class="th_deg">Image</th>
                    <th class="th_deg">Delete</th>
                    <th class="th_deg">Edit</th>
                </tr>

                @foreach($data as $data)
                <tr>
                    <td>{{ $data->room_title }}</td>
                    <td>{!! Str::limit($data->description,150) !!}</td>
                    <td>RM {{ $data->price }}</td>
                    <td>{{ $data->room_quantity }}</td>
                    <td>{{ $data->wifi }}</td>
                    <td>{{ $data->room_type }}</td>
                    <td>
                        <img width="200" src="room/{{ $data->image }}">
                    </td>
                    <td>
                        <a onclick="return confirm ('confirm Delete ?')" class="btn btn-danger" href="{{ url('delete_room',$data->id)}}">Delete</a>
                    </td>
                    <td>
                        <a class="btn btn-warning" href="{{ url('edit_room',$data->id)}}">Edit</a>
                    </td>
                </tr>
                @endforeach
            </table>
          </div>
        </div>
    </div>

    @include('admin.footer')
  </body>
</html>
