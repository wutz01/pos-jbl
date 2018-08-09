@extends('templates.skeleton')
@section('location', 'Reports')
@section('title', 'Reports')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="content">
          <div class="row">
            <div class="col-xs-5">
              <div class="icon-big icon-success text-center">
                <i class="ti-wallet"></i>
              </div>
            </div>
            <div class="col-xs-7">
              <div class="numbers">
                <p>Daily Sales</p>
                <span style="font-size: 15px;">&#8369; {{ number_format($todaySales, 2, '.', ',') }}</span>
              </div>
            </div>
          </div>
          <div class="footer">
            <hr />
            <div class="stats">
              <i class="ti-calendar"></i> Today
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="content">
          <div class="row">
            <div class="col-xs-5">
              <div class="icon-big icon-success text-center">
                <i class="ti-wallet"></i>
              </div>
            </div>
            <div class="col-xs-7">
              <div class="numbers">
                <p>Weekly Sales</p>
                <span style="font-size: 15px;">&#8369; {{ number_format($weeklySales, 2, '.', ',') }}</span>
              </div>
            </div>
          </div>
          <div class="footer">
            <hr />
            <div class="stats">
              <i class="ti-calendar"></i> Week
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="card">
        <div class="content">
          <div class="row">
            <div class="col-xs-5">
              <div class="icon-big icon-success text-center">
                <i class="ti-wallet"></i>
              </div>
            </div>
            <div class="col-xs-7">
              <div class="numbers">
                <p>Monthly Sales</p>
                <span style="font-size: 15px;">&#8369; {{ number_format($monthlySales, 2, '.', ',') }}</span>
              </div>
            </div>
          </div>
          <div class="footer">
            <hr />
            <div class="stats">
              <i class="ti-calendar"></i> Month
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script>
  $(function() {
    $(".nav li").removeClass("active");
    $(".side-nav-reports").addClass("active");
  })
</script>
@endsection
