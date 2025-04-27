<!DOCTYPE html>
<html lang="en">
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
                    <h1 class="booking_title">Edit Room</h1>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('update_room',$data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="div_deg">
                            <label>Room Title</label>
                            <input type="text" name="title" value="{{ $data->room_title }}">
                        </div>

                        <div class="div_deg">
                            <label>Description</label>
                            <textarea name="description">{{ $data->description }}</textarea>
                        </div>

                        <div class="div_deg">
                            <label>Price</label>
                            <input type="number" name="price" value="{{ $data->price }}">
                        </div>

                        <div class="div_deg">
                            <label>Room Quantity</label>
                            <input type="number" name="room_quantity" value="{{ $data->room_quantity }}">
                        </div>

                        <div class="div_deg">
                            <label>Pax Number limit</label>
                            <input type="number" name="pax_number" value="{{ $data->pax_number }}">
                        </div>

                        <div class="div_deg">
                            <label>Room Type</label>
                            <select name="type">
                                <option selected value="{{ $data->room_type }}">{{ $data->room_type }}</option>
                                <option value="regular">Regular</option>
                                <option value="premium">Premium</option>
                                <option value="deluxe">Deluxe</option>
                            </select>
                        </div>

                        <div class="div_deg">
                            <label>Free Wifi</label>
                            <select name="wifi">
                                <option selected value="{{ $data->wifi }}">{{ $data->wifi }}</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="div_deg">
                            <label>Current Image</label>
                            <img style="margin:auto"; src="/room/{{ $data->image }}">
                        </div>

                        <div class="div_deg">
                            <label>Upload Image</label>
                            <input type="file" name="image">
                        </div>

                        <div class="div_deg">
                            <input class="btn btn-primary" type="submit" value="Edit Room">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')
</body>
</html>
