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
            padding-top: 10px;
        }

        .div_center {
            text-align: center;
            padding-top: 40px;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
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
                    <h1 style="font-size: 30px; font-weight:bold;">Add Staff</h1>


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

                    <form action="{{ url('register_staff') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="div_deg">
                            <label>Staff Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="div_deg">
                            <label>Staff Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="div_deg">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="div_deg">
                            <label>Password</label>
                            <input type="password" name="password" required>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="div_deg">
                            <label>Staff Type</label>
                            <select name="usertype">
                                <option value="staff" {{ old('usertype') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="manager" {{ old('usertype') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="div_deg">
                            <input class="btn btn-primary" type="submit" value="Register">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.footer')
  </body>
</html>
