<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta name="author" content="John Perez" />
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JBL Pharmacy - Login</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
  	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paper-dashboard-pro.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}" type="text/css" />
</head>

<body>
  <nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}">JBL Pharmacy</a>
      </div>
      <!-- <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="register.html">
              Register
            </a>
          </li>
          <li>
            <a href="../dashboard/overview.html">
              Dashboard
            </a>
          </li>
        </ul>
      </div> -->
    </div>
  </nav>

  <div class="wrapper wrapper-full-page">
    <div class="full-page login-page" data-color="blue" data-image="{{ asset('img/background/background-2.jpg') }}">
      <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
      <div class="content">
        <div class="container">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
              <form method="POST" action="{{ route('authenticate') }}" id="frm-login">
                <div class="card" data-background="color" data-color="blue">
                  <div class="card-header">
                    <h3 class="card-title">Login</h3>
                  </div>
                  <div class="card-content">
                    <div class="error-wrapper"></div>
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" name="username" placeholder="Enter username" class="form-control input-no-border">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" name="password" placeholder="Password" class="form-control input-no-border">
                    </div>
                  </div>
                  <div class="card-footer text-center">
                    <button type="submit" class="btn btn-fill btn-wd ">Let's go</button>
                    <div class="forgot">
                      <a href="#pablo">Forgot your password?</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer footer-transparent">
        <div class="container">
          <div class="copyright">
            © <script>document.write(new Date().getFullYear())</script>, made by <a href="#">John Perez</a>
          </div>
        </div>
        </footer>
      <div class="full-page-background" style="background-image: url({{ asset('img/background/background-2.jpg') }})"></div>
    </div>
  </div>
</body>

<script src="{{ asset('js/jquery-1.10.2.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-checkbox-radio.js') }}"></script>
<script src="{{ asset('js/chartist.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
<script src="{{ asset('js/paper-dashboard.js') }}"></script>
<script src="{{ asset('js/jquery-form.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>

<script type="text/javascript">
  $(function(){
    var loader = "{{ asset('img/loading.gif') }}";
    /* AJAX SETUP */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#frm-login").validate();
    $("#frm-login").ajaxForm({
      // dataType identifies the expected content type of the server response
      dataType:  'json',
      // success identifies the function to invoke when the server response
      // has been received
      success: function (data) {
        if (data.is_successful) {
          $.notify({
            icon: 'ti-check',
            message: data.message
          },{
              type: 'success',
              timer: 2000
          });
          setTimeout(() => {
            window.location.href=data.redirect_url
          }, 1000)
        } else {
          $(".error-wrapper").fadeIn(300);
          var html = '';
          html += '<div class="alert alert-danger" role="alert">';
          html += data.message;
          html += '</div>';
          $(".error-wrapper").html(html);
        }
      },

      error: function (e) {
        console.log(`error: `, e)
        $(".error-wrapper").html("<span style='color: red'>Oops! Something went wrong. Please try again later.</span>");
        $(".error-wrapper").fadeIn(300);
      },

      beforeSubmit: function () {
        $(".error-wrapper").html('');
      }
    });
  });
</script>
</body>
</html>
