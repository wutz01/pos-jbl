@extends('templates.skeleton')
@section('location', 'Point of Sales')
@section('title', 'Point of Sales')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 col-md-5">
      <div class="card">
        <div class="content">
          <div class="row">
              <div class="col-md-8">
                <!-- <select class="select2" name="productId" style="width: 180px; height: 50px">
                  <option value="test">test</option>
                </select> -->
                <input type="text" name="productId" value="" class="autocomplete product">
              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-success btn-icon btn-xs" id="add-product-btn" rel="tooltip" data-placement="top" title="Add Product" trigger><i class="fa fa-plus"></i> ADD</button>
              </div>
          </div>
        </div>
      </div>
      <div class="card card-user">
        <div class="image">
          <img src="{{ asset('img/background.jpg') }}" alt="..."/>
        </div>
        <div class="content">
          <div class="author">
            <img class="avatar border-white" src="{{ asset('img/faces/face-4.jpg') }}" alt="..."/>
            <h4 class="title"><span id="label-medicineName">Medicine Name</span>
            </h4>
          </div>
          <p class="description text-center">
            Description
          </p>
        </div>
        <hr>
        <div class="text-center">
          <div class="row">
              <div class="col-md-3 col-md-offset-2">
                  <h5><span id="label-stock">0</span><br /><small id="label-stock-qty">pcs</small></h5>
              </div>
              <div class="col-md-4">
                  <h5>P <span id="label-pricePerPiece">0.00</span><br /><small>per Piece</small></h5>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-7">
      <div class="card">
        <div class="content">
          <div class="card card-plain">
            <div class="header">
                <h4 class="title">Orders</h4>
                <p class="category">Reminder: Items past 5 Minutes can't be updated / remove.</p>
            </div>
            <div class="content table-responsive table-full-width">
              <table class="table table-hover">
                <thead>
                  <th width="10%"></th>
                  <th width="55%">Medicine Name</th>
                  <th width="10%">Quantity</th>
                  <th width="10%">Price</th>
                  <th width="5%">Disc (%)</th>
                  <th width="10%">Total</th>
                </thead>
                <tbody class="orders-container">

                </tbody>
              </table>
              <button type="button" class="btn btn-success btn-large btn-block" id="btn-finalize" name="button">SAVE ORDER</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('javascript')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/jquery.autocomplete.min.js') }}"></script>
<script>
  var productId = null;
  $(function() {
    $(".nav li").removeClass("active");
    $(".side-nav-sales").addClass("active");
    loadCart();

    $(".autocomplete").autocomplete({
      serviceUrl: "{{ route('sales.load.inventory') }}",
      onSelect: function (suggestion) {
        if (!suggestion) return false
        updateStockLabels(suggestion.data)
      }
    });

    $("#btn-finalize").on('click', function () {
      $("#btn-finalize").attr('disabled', 'disabled');
      $.post("{!! route('finalize') !!}", {}, (o) => {
        if (o.is_successful) {
          $.notify({
            icon: 'ti-check',
            message: o.message
          },{
              type: 'success',
              timer: 2000
          });
          loadCart();
        } else {
          $.notify({
            icon: 'ti-close',
            message: o.message
          },{
            type: 'warning',
            timer: 2000
          });
        }
        $("#btn-finalize").removeAttr('disabled');
      }, 'json');
    });

    $("#add-product-btn").on('click', function () {
      if (!productId) return false
      $.confirm({
        title: 'Order',
        type: 'green',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>How many quantity to order?</label>' +
        '<input type="number" placeholder="0" class="quantity form-control" required />' +
        '<div class="checkbox">' +
        '<label><input type="checkbox" class="useBulk form-control" value="bulk"/> Use Bulk Price?</label>' +
        '</div></div>' +
        '</form>',
        buttons: {
          formSubmit: {
            text: 'Add',
            btnClass: 'btn-green',
            action: function () {
              var quantity = this.$content.find('.quantity').val();
              var useBulk = this.$content.find('.useBulk').is(':checked');
              if(!quantity || !Number.isInteger(parseInt(quantity)) || parseInt(quantity) <= 0){
                  $.alert('Provide a valid quantity');
                  return false;
              }
              $.post("{!! route('add-to-cart') !!}", {productId: productId, quantity: quantity, useBulk: useBulk}, (o) => {
                if (o.is_successful) {
                  $.notify({
                    icon: 'ti-check',
                    message: o.message
                  },{
                      type: 'success',
                      timer: 2000
                  });
                  loadCart();
                  console.log(`stocks:`, o.stock);
                  updateStockLabels(o.stock);
                } else {
                  $.notify({
                    icon: 'ti-close',
                    message: o.message
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

  }); // end document ready

  function updateStockLabels (data) {
    productId = data.id;
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
      $("#label-stock-qty").html('pc')
    } else {
      $("#label-stock-qty").html('piece')
    }

    $("#label-pricePerPiece").html(number_format(data.pricePerPiece, 2, '.', ','))
    $("#label-supplierPrice").html(number_format(data.supplierPrice, 2, '.', ','))
  }

  function loadCart () {
    $.get("{!! route('sales.load.cart') !!}", {}, (o) => {
      $('.orders-container').html(o);
      $('.autocomplete').val('');
    });
  }
</script>

<style>
  .table-input { width: 70px; text-align: center }
  .product { height: 37px; width: 190px; }
  .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; cursor: pointer; }
  .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
  .autocomplete-selected { background: #F0F0F0; }
  .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
  .autocomplete-group { padding: 2px 5px; }
  .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>
@endsection
