@extends('templates.skeleton')
@section('location', 'Reports')
@section('title', 'Reports')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-3 col-sm-6">
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
              <i class="ti-download"></i> <a href="{{ route('reports.today') }}" target="_blank">Today</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(Auth::user()->hasRole('owner'))
    <div class="col-lg-3 col-sm-6">
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
              <i class="ti-download"></i> <a href="{{ route('reports.weekly') }}" target="_blank">Week</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
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
              <i class="ti-download"></i> <a href="{{ route('reports.monthly') }}" target="_blank">Month</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="col-lg-3 col-sm-6">
      <div class="card">
        <div class="content">
          <div class="row">
            <div class="col-xs-5">
              <div class="icon-big icon-success text-center">
                <i class="ti-view-list"></i>
              </div>
            </div>
            <div class="col-xs-7">
              <div class="numbers">
                <p>Inventory</p>
                <span style="font-size: 15px;">-</span>
              </div>
            </div>
          </div>
          <div class="footer">
            <hr />
            <div class="stats">
              <i class="ti-download"></i> <a href="{{ route('reports.ending.inventory') }}" target="_blank">Ending Inventory</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if(Auth::user()->hasRole('owner'))
  <div class="row">
    <div class="col-lg-12 col-sm-12">
      <div class="card">
        <div class="content">
          <div class="row">
            <div class="col-lg-4">
              <input type="date" id="startDate" value="" class="form-control">
            </div>
            <div class="col-lg-4">
              <input type="date" id="endDate" value="" class="form-control">
            </div>
            <div class="col-lg-4">
              <button type="button" class="btn btn-primary btn-block btn-large" id="generate-btn" name="button">Generate</button>
            </div>
          </div>
          <div id="report"></div>
        </div>
        <div class="footer">
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@section('javascript')
<script>
  $(function() {
    $(".nav li").removeClass("active");
    $(".side-nav-reports").addClass("active");

    $("#generate-btn").on('click', function () {
      var startDate = $("#startDate").val();
      var endDate   = $("#endDate").val();

      if (!startDate || !endDate) {
        $.alert({
          type: 'red',
          content: 'Enter Start and End Date',
          title: 'Error!'
        });
        return false
      }
      $.get("{!! route('report-generate-date-range') !!}", {startDate: startDate, endDate: endDate}, function (o) {
        $("#report").html(o);
      });
    });
  })
</script>
@endsection
