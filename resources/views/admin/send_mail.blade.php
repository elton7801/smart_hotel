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

            <center>
                <h1 style="font-size:30px; font-weight:bold;">Mail send to {{ $data->name }}</h1>

                <form action="{{ url('mail',$data->id) }}" method="post">
                    @csrf
                    <div class="div_deg">
                        <label>Greeting</label>
                        <input type="text" name="greeting" required>
                    </div>

                    <div class="div_deg">
                        <label>Mail Body</label>
                        <textarea name="body" required></textarea>
                    </div>

                    <div class="div_deg">
                        <label>Action Text</label>
                        <input type="text" name="action_text" required>
                    </div>

                    <div class="div_deg">
                        <label>Action Url</label>
                        <input type="text" name="action_url">
                    </div>

                    <div class="div_deg">
                        <label>End Line</label>
                        <input type="text" name="endline">
                    </div>
                    <div class="div_deg">
                        <input class="btn btn-primary" type="submit" value="Send Mail">
                    </div>
                </form>
            </center>
          </div>
        </div>
    </div>

    @include('admin.footer')
  </body>
</html>
