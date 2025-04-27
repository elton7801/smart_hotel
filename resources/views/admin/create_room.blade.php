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
        .div_center
        {
            text-align: center;
            padding-top: 40px;
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
                    <h1 style="font-size: 30px; font-weight:bold;">Add Room</h1>
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
                    <form action="{{ url('add_room') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="div_deg">
                            <label>Room Title</label>
                            <input type="text" name="title" required>
                        </div>

                        <div class="div_deg">
                            <label>Description</label>
                            <textarea name="description" required></textarea>
                        </div>

                        <div class="div_deg">
                            <label>Price</label>
                            <input type="number" name="price" required>
                        </div>

                        <div class="div_deg">
                            <label>Room Quantity</label>
                            <input type="number" name="room_quantity" required>
                        </div>

                        <div class="div_deg">
                            <label>Pax Number Limit</label>
                            <input type="number" name="pax_number" required>
                        </div>

                        <div class="div_deg">
                            <label>Room Type</label>
                            <select name="type">
                                <option value="regular">Regular</option>
                                <option value="premium">Premium</option>
                                <option value="deluxe">Deluxe</option>
                            </select>
                        </div>

                        <div class="div_deg">
                            <label>Free Wifi</label>
                            <select name="wifi">
                                <option selected value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="div_deg">
                            <label>Upload Image</label>
                            <input type="file" name="image">
                        </div>

                        <div class="div_deg">
                            <input class="btn btn-primary" type="submit" value="Add Room">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')
</body>
</html>
