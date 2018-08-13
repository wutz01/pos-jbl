@extends('templates.skeleton')
@section('location', 'Inventory > View Item')
@section('title', 'Inventory > View Item')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 col-md-5">
        <div class="card card-user">
            <div class="image">
                <img src="{{ asset('img/background.jpg') }}" alt="..."/>
            </div>
            <div class="content">
                <div class="author">
                  <img class="avatar border-white" src="{{ asset('img/faces/face-4.jpg') }}" alt="..."/>
                  <h4 class="title"><span id="label-medicineName">{{ $stock->medicineName }}</span><br />
                     <a href="#"><small id="label-supplierName">{{ ($stock->supplierName ? $stock->supplierName : 'Supplier not available') }}</small></a>
                  </h4>
                </div>
                <p class="description text-center">
                    {{ ($stock->description ? $stock->description : 'No Description Available') }}
                </p>
            </div>
            <hr>
            <div class="text-center">
                <div class="row">
                    <div class="col-md-2 col-md-offset-1">
                        <h5><span id="label-stock">{{ $stock->stockQty }}</span><br /><small id="label-stock-qty">{{ ($stock->stockQty > 1 ? 'pcs' : 'piece') }}</small></h5>
                    </div>
                    <div class="col-md-4">
                        <h5>P <span id="label-pricePerPiece">{{ number_format($stock->pricePerPiece, 2, '.', ',') }}</span><br /><small>per Piece</small></h5>
                    </div>
                    <div class="col-md-4">
                        <h5>P <span id="label-supplierPrice">{{ number_format($stock->supplierPrice, 2, '.', ',') }}</span><br /><small>Supplier Price</small></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="actions-card">
            <div class="header">
                <h4 class="title">Actions</h4>
            </div>
            <div class="content">
              <div class="row">
                <div class="col-md-2">
                  <button type="button" class="btn btn-success btn-icon" id="add-qty-btn" rel="tooltip" data-placement="bottom" title="Add Quantity" trigger {{ ($stock->status === 'INACTIVE' ? 'disabled' : '') }}><i class="fa fa-plus"></i></button>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger btn-icon" id="remove-qty-btn" rel="tooltip" data-placement="bottom" title="Remove Quantity" trigger {{ ($stock->status === 'INACTIVE' ? 'disabled' : '') }}><i class="fa fa-minus"></i></button>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-info btn-icon" id="edit-inventory-btn" rel="tooltip" data-placement="bottom" title="Edit Inventory" {{ ($stock->status === 'INACTIVE' ? 'disabled' : '') }}><i class="fa fa-pencil"></i></button>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger btn-icon" id="delete-inventory-btn" rel="tooltip" data-placement="bottom" title="Delete Inventory" {{ ($stock->status === 'INACTIVE' ? 'disabled' : '') }}><i class="fa fa-trash"></i></button>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-7">
      <div class="card">
        <div class="content">
          <div id="stock-trail">
          </div>
          <div class="error-wrapper"></div>
          <form action="{!! route('inventory.update', ['id' => $stock->id]) !!}" method="post" id="update-inventory-form">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Medicine Name</label>
                          <input type="text" class="form-control border-input" name="medicineName" placeholder="Medicine Name" value="{{ $stock->medicineName }}" required>
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label>Product Type</label>
                          <input type="text" class="form-control border-input" name="productType" placeholder="CAPSULE / Etc" value="{{ $stock->medicineType }}">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Price Per Piece</label>
                          <input type="text" class="form-control border-input price" name="pricePerPiece" placeholder="0.00" value="{{ $stock->pricePerPiece }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Bulk Price</label>
                          <input type="text" class="form-control border-input price" name="bulkPrice" placeholder="0.00" value="{{ $stock->bulkPrice }}" required>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Supplier Name</label>
                          <input type="text" class="form-control border-input" name="supplierName" placeholder="Supplier Name" value="{{ $stock->supplierName }}">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Supplier Price</label>
                          <input type="text" class="form-control border-input price" name="supplierPrice" placeholder="0.00" value="{{ $stock->supplierPrice }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label>Quantity Stock</label>
                          <input type="number" class="form-control border-input stock" name="quantityStock" placeholder="0" value="{{ $stock->stockQty }}" disabled>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label>Description</label>
                          <textarea rows="5" class="form-control border-input" name="description" placeholder="Here can be your description">{{ $stock->description }}</textarea>
                      </div>
                  </div>
              </div>
              <div class="text-center">
                  <button type="submit" id="btn-update" class="btn btn-success btn-fill btn-wd">Update</button>
                  <button type="button" id="btn-cancel-update" class="btn btn-default btn-fill btn-wd">Cancel</button>
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
    toggleEditForm('hide');

    $("#edit-inventory-btn").on('click', () => {
      toggleEditForm('show');
    });

    $("#btn-cancel-update").on('click', () => {
      toggleEditForm('hide');
    });

    $("#delete-inventory-btn").on('click', () => {
      $.confirm({
        icon: 'fa fa-warning',
        title: 'Attention!',
        content: 'This will archive your inventory. your sales data will not be affected.',
        type: 'red',
        typeAnimated: true,
        buttons: {
          confirm: {
            text: 'Ok',
            btnClass: 'btn-red',
            keys: ['enter'],
            action: () => {
              $.post("{!! route('inventory.archive', ['id' => $stock->id]) !!}", {}, function (o) {
                if (o.is_successful) {
                  $.notify({
                    icon: 'ti-check',
                    message: o.message
                  },{
                      type: 'success',
                      timer: 2000
                  });
                  loadTrail();

                  setTimeout(function () {
                    window.location.href = "{{ route('inventory.index') }}";
                  }, 2000)
                } else {
                  console.log('failed');
                  $.notify({
                    icon: 'ti-check',
                    message: "Failed updating stock status"
                  },{
                    type: 'warning',
                    timer: 2000
                  });
                }
              }, 'json');
            }
          },
          cancel: {
            text: 'Cancel',
            btnClass: 'btn-default',
            keys: ['esc'],
            action: () => {
            }
          }
        }
      });
    });

    $("#add-qty-btn").on('click', () => {
      $.confirm({
        title: 'Add Quantity',
        type: 'green',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>How many quantity to add?</label>' +
        '<input type="number" placeholder="Quantity" class="quantity form-control" value="0" required />' +
        '</div>' +
        '</form>',
        buttons: {
          formSubmit: {
            text: 'Add',
            btnClass: 'btn-green',
            action: function () {
              var quantity = this.$content.find('.quantity').val();
              if(!quantity || !Number.isInteger(parseInt(quantity)) || parseInt(quantity) <= 0){
                  $.alert('provide a valid quantity');
                  return false;
              }
              $.post("{!! route('inventory.update.quantity', ['id' => $stock->id]) !!}", {quantity: quantity, type: "ADD"}, (o) => {
                if (o.is_successful) {
                  $.notify({
                    icon: 'ti-check',
                    message: o.message
                  },{
                      type: 'success',
                      timer: 2000
                  });
                  updateStockLabels(o.stock);
                  loadTrail();
                } else {
                  console.log('failed');
                  $.notify({
                    icon: 'ti-check',
                    message: "Failed adding quantity to our inventory"
                  },{
                    type: 'warning',
                    timer: 2000
                  });
                }
              }, 'json');
            }
          },
          cancel: function () {
            //close
          }
        },
        onContentReady: function () {
          // bind to events
          var jc = this;
          this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
          });
        }
      });
    });

    $("#remove-qty-btn").on('click', () => {
      $.confirm({
        title: 'Remove Quantity',
        type: 'red',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>How many quantity to remove?</label>' +
        '<input type="number" placeholder="Quantity" class="quantity form-control" value="0" required />' +
        '</div>' +
        '</form>',
        buttons: {
          formSubmit: {
            text: 'Remove',
            btnClass: 'btn-red',
            action: function () {
              var quantity = this.$content.find('.quantity').val();
              if(!quantity || !Number.isInteger(parseInt(quantity)) || parseInt(quantity) <= 0){
                  $.alert('provide a valid quantity', 'error');
                  return false;
              }
              $.post("{!! route('inventory.update.quantity', ['id' => $stock->id]) !!}", {quantity: quantity, type: "REMOVED"}, (o) => {
                if (o.is_successful) {
                  $.notify({
                    icon: 'ti-check',
                    message: o.message
                  },{
                      type: 'success',
                      timer: 2000
                  });
                  updateStockLabels(o.stock);
                  loadTrail();
                } else {
                  $.notify({
                    icon: 'ti-check',
                    message: "Failed removing quantity to our inventory"
                  },{
                    type: 'warning',
                    timer: 2000
                  });
                }
              }, 'json');
            }
          },
          cancel: function () {
            //close
          }
        },
        onContentReady: function () {
          // bind to events
          var jc = this;
          this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
          });
        }
      });
    });

    $("#update-inventory-form").validate();
    $("#update-inventory-form").ajaxForm({
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
          toggleEditForm('hide');
          updateStockLabels(data.stock)

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
        $("#btn-update").removeAttr('disabled');
      },

      error: function (e) {
        console.log(`error: `, e)
        $(".error-wrapper").html("<span style='color: red'>Oops! Something went wrong. Please try again later.</span>");
        $(".error-wrapper").fadeIn(300);
      },

      beforeSubmit: function () {
        $(".error-wrapper").html('');
        $("#btn-update").attr('disabled', 'disabled');
      }
    });
  });

  function loadTrail () {
    var loader = "<img src='{{ asset('img/loading.gif') }}'>";
    $("#stock-trail").show();
    $("#stock-trail").html(loader);
    $.get("{!! route('inventory.trail', ['id' => $stock->id]) !!}", {}, (o) => {
      $("#stock-trail").html(o);
    });
  }

  function toggleEditForm (toggle) {
    if (toggle === 'show') {
      $("#stock-trail").hide();
      $("#update-inventory-form").show();
      $("#actions-card").hide();
    } else {
      $("#update-inventory-form").hide();
      $("#actions-card").show();
      loadTrail();
    }
  }

  function updateStockLabels (data) {
    $("#label-medicineName").html(data.medicineName)
    if (data.description) {
      $(".description").html(data.description)
    } else {
      $(".description").html('No Description available')
    }

    if (data.supplierName) {
      $("#label-supplierName").html(data.supplierName)
    } else {
      $("#label-supplierName").html('Supplier not available')
    }

    $("#label-stock").html(data.stockQty)
    if (parseInt(data.stockQty) > 1) {
      $("#label-stock-qty").html('pcs')
    } else if (parseInt(data.stockQty) === 0) {
      $("#label-stock-qty").html('')
    } else {
      $("#label-stock-qty").html('piece')
    }

    $("#label-pricePerPiece").html(number_format(data.pricePerPiece, 2, '.', ','))
    $("#label-supplierPrice").html(number_format(data.supplierPrice, 2, '.', ','))
  }

  function number_format(number, decimals, dec_point, thousands_point) {
    number = parseFloat(number);
    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
  }
</script>
@endsection
