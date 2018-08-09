@extends('templates.skeleton')
@section('location', 'Inventory > New Stock')
@section('title', 'Inventory > New Stock')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card" style="padding: 10px 10px;">
        <div class="content">
          <div class="error-wrapper"></div>
          <form action="{{ route('inventory.save') }}" method="post" id="create-inventory-form">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Medicine Name</label>
                          <!-- <input type="text" class="form-control border-input" disabled placeholder="Company" value="Creative Code Inc."> -->
                          <input type="text" class="form-control border-input" name="medicineName" placeholder="Medicine Name" value="" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Price Per Piece</label>
                          <input type="text" class="form-control border-input price" name="pricePerPiece" placeholder="0.00" value="0.00" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Bulk Price</label>
                          <input type="text" class="form-control border-input price" name="bulkPrice" placeholder="0.00" value="0.00" required>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Supplier Name</label>
                          <input type="text" class="form-control border-input" name="supplierName" placeholder="Supplier Name" value="">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Supplier Price</label>
                          <input type="text" class="form-control border-input price" name="supplierPrice" placeholder="0.00" value="0.00" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Quantity Stock</label>
                          <input type="number" class="form-control border-input stock" name="quantityStock" placeholder="0" value="0" required>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label>Description</label>
                          <textarea rows="5" class="form-control border-input" name="description" placeholder="Here can be your description"></textarea>
                      </div>
                  </div>
              </div>
              <div class="text-center">
                  <button type="submit" class="btn btn-success btn-fill btn-wd">Save</button>
              </div>
              <div class="clearfix"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('js/jquery-form.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script>
  $(function() {
    $(".nav li").removeClass("active");
    $(".side-nav-inventory").addClass("active");

    $("#create-inventory-form").validate();
    $("#create-inventory-form").ajaxForm({
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
          $(".border-input").val('');
          $(".price").val('0.00');
          $(".stock").val(0);
        } else {
          $(".error-wrapper").fadeIn(300);
          var html = '';
          html += '<div class="alert alert-danger" role="alert"><strong>Oops! We have found some errors!</strong><br>';
          html += '<ul>';
          $.each(data.validator_error, function(i,item){
            html += '<li>' + item + '</li>';
          });
          html += '</ul>';
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
  })
</script>
@endsection
