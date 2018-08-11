@extends('templates.skeleton')
@section('location', 'Inventory')
@section('title', 'Inventory')
@section('toolbar')
<li>
  <a href="{{ route('inventory.new') }}">
    <i class="ti-plus"></i>
    <p>Add Inventory</p>
  </a>
</li>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card" style="padding: 10px 10px;">
        <div class="content table-responsive table-full-width">
          <table class="table table-striped datatable display compact">
            <thead>
              <th>ID</th>
              <th>Medicine Name</th>
              <th>Product Type</th>
              <th>Quantity</th>
              <th>Price / Pc</th>
              <th>Bulk Price</th>
              <th>Supplier</th>
              <th>Supplier's Price</th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('javascript')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.css') }}" type="text/css" />
<script src="{{ asset('js/jquery.dataTables.js') }}"></script>
<script>
  $(function() {
    $(".nav li").removeClass("active");
    $(".side-nav-inventory").addClass("active");

    var table = $(".datatable").DataTable({
      processing: true,
      serverSide: true,
      ajax: "{!! route('inventory.load.data') !!}",
      columns: [
          {data: 'id', name: 'id'},
          {data: 'medicineName', name: 'medicineName'},
          {data: 'medicineType', name: 'medicineType'},
          {data: 'stockQty', name: 'stockQty'},
          {data: 'pricePerPiece', name: 'pricePerPiece'},
          {data: 'bulkPrice', name: 'bulkPrice'},
          {data: 'supplierName', name: 'supplierName'},
          {data: 'supplierPrice', name: 'supplierPrice'}
        ]
    });

    $('.datatable tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        console.log(`data`, data);
        window.location.href=data.viewUrl
        // alert( 'You clicked on '+data[1]+'\'s row' );
    });
  })
</script>

<style>
  .datatable tbody tr {
    cursor: pointer;
  }
</style>
@endsection
