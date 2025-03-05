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
                    <h1 style="font-size: 30px; font-weight:bold;">Add Staff</h1>
                    <form action="{{ url('register_staff') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="div_deg">
                            <label>Staff Name</label>
                            <input type="text" name="name" required>
                        </div>

                        <div class="div_deg">
                            <label>Staff Email</label>
                            <input name="email" required></input>
                        </div>

                        <div class="div_deg">
                            <label>Phone</label>
                            <input type="number" name="phone" required>
                        </div>

                        <div class="div_deg">
                            <label>Password</label>
                            <input type="number" name="password" required>
                        </div>

                        <div class="div_deg">
                            <label>Staff Type</label>
                            <select name="usertype">
                                <option value="staff">staff</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
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
    <script src="//code.tidio.co/2zz9vpjtxroyrgjo1yprppzulcongdjp.js" async></script>
  </body>
</html>
