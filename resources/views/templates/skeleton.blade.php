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
    <title>JBL Pharmacy - @yield('title')</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
  	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paper-dashboard.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/craftpip.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}" type="text/css" />
</head>

<body>
  <div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">
    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	  -->
    	@include('templates.left-sidebar')
    </div>

    <div class="main-panel">
      @include('templates.navs')
      <div class="content">
          @yield('content')
      </div>
      @include('templates.footer')
      @yield('modal')
    </div>
  </div>
</body>

<script src="{{ asset('js/jquery-1.10.2.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-checkbox-radio.js') }}"></script>
<script src="{{ asset('js/chartist.min.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
<!--  Google Maps Plugin    -->
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script> -->
<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="{{ asset('js/craftpip.js') }}"></script>
<script src="{{ asset('js/paper-dashboard.js') }}"></script>
<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('js/demo.js') }}"></script>
<script type="text/javascript">
var idleTime = 0;
  $(function(){
    var loader = "{{ asset('img/loading.gif') }}";
    /* AJAX SETUP */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
        console.log(`[MOUSE MOVEMENT]`)
    });
    $(this).keypress(function (e) {
        idleTime = 0;
        console.log(`[KEYPRESS]`)
    });
  });

  function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 19) { // 20 minutes
        window.location.reload();
    }
  }
</script>
@yield('javascript')
@yield('footer')

</body>
</html>
