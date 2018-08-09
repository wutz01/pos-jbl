@extends('templates.skeleton')
@section('location', 'Dashboard')
@section('title', 'Dashboard')
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
    <div class="row">
      <div class="col-md-12">
          <div class="card ">
              <div class="header">
                  <h4 class="title">{{ date('Y') }} Sales</h4>
                  <p class="category">Net Sales</p>
              </div>
              <div class="content">
                  <div id="chartActivity" class="ct-chart"></div>

                  <div class="footer">
                      <div class="chart-legend">
                          <i class="fa fa-circle text-info"></i> Net Sales
                      </div>
                      <hr>
                      <div class="stats">
                          <i class="ti-check"></i> Data information certified
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-12">
          <div class="card ">
              <div class="header">
                  <h4 class="title">{{ date('Y') }} Sales</h4>
                  <p class="category">Gross Sales</p>
              </div>
              <div class="content">
                  <div id="chartGrossSales" class="ct-chart"></div>

                  <div class="footer">
                      <div class="chart-legend">
                          <i class="fa fa-circle text-info"></i> Gross Sales
                      </div>
                      <hr>
                      <div class="stats">
                          <i class="ti-check"></i> Data information certified
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
    // $(".nav li").removeClass("active");
    var data = {
      // A labels array that can contain any sort of values
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      // Our series array that contains series objects or in this case series data arrays
      series: [
        {{ $yearlySales }}
      ]
    };

    var data2 = {
      // A labels array that can contain any sort of values
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      // Our series array that contains series objects or in this case series data arrays
      series: [
        {{ $grossSales }}
      ]
    };

    // As options we currently only set a static size of 300x200 px
    var options = {
        seriesBarDistance: 10,
        axisX: {
            showGrid: false
        },
        height: "245px"
    };

    new Chartist.Line('#chartActivity', data, options);


    new Chartist.Line('#chartGrossSales', data2, options);


    // $.notify({
    //   icon: 'ti-home',
    //   message: "Welcome User!"
    //
    // },{
    //     type: 'success',
    //     timer: 4000
    // });
  })
</script>
@endsection
