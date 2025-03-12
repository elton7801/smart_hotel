<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')
    <style>
        .table_deg {
            border: 2px solid white;
            margin: auto;
            width: 80%;
            text-align: center;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .th_deg {
            background-color: skyblue;
            padding: 15px;
        }
        tr {
            border: 3px solid white;
        }
        td {
            padding: 10px;
        }
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
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
                <thead>
                    <tr>
                        <th class="th_deg">Name</th>
                        <th class="th_deg">Email</th>
                        <th class="th_deg">Phone</th>
                        <th class="th_deg">Message</th>
                        <th class="th_deg">Send Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->message }}</td>
                        <td>
                            <a class="btn btn-success" href="{{ url('send_mail', $item->id) }}">Send mail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-container">
                {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
    </div>

    @include('admin.footer')
  </body>
</html>
