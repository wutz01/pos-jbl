@if(count($items) <= 0)
<tr class="no-data-available">
  <td colspan="6" style="text-align: center">No Data available</td>
</tr>
@else
  @foreach($items as $item)
    @php
      $now = Carbon\Carbon::now();
      $disable = ($now->diffInMinutes($item->created_at) > 5 ? true : false)
    @endphp
    <tr class="product-{{ $item->id }}">
      <td>
        <button type="button" class="btn btn-danger btn-xs btn-remove-item" rel="tooltip" data-placement="top" title="Remove" data-id="{{ $item->id }}" {{ $disable ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
      </td>
      <td>{{ $item->inventory->medicineName }}</td>
      <td><input type="number" value="{{ $item->quantity }}" name="order[{{ $item->id }}][quantity]" class="quantity-text table-input" id="input-quantity-{{ $item->id }}" data-id="{{ $item->id }}" readonly></td>
      <td><input type="text" value="{{ $item->isBulk ? $item->bulkPrice : $item->pricePerPiece }}" name="order[{{ $item->id }}][price]" class="price table-input" id="input-price-{{ $item->id }}" data-id="{{ $item->id }}" readonly></td>
      <td><input type="number" value="{{ $item->discount }}" class="discount table-input" name="order[{{ $item->id }}][discount]" id="input-discount-{{ $item->id }}" data-id="{{ $item->id }}" {{ $disable ? 'readonly' : '' }}></td>
      <td><input type="text" value="{{ $item->totalPrice }}" class="price table-input total-amount-item" name="order[{{ $item->id }}][total_amount]" readonly id="input-total-{{ $item->id }}" data-id="{{ $item->id }}"></td>
    </tr>
  @endforeach
    <tr>
      <td>-</td>
      <td style="text-align: right;">Total Quantity:</td>
      <td style="text-align: center;" class="total-quantity">0</td>
      <td style="text-align: right;">Global Discount:</td>
      <td><input type="number" value="{{ $order->globalDiscount }}" class="discount table-input globalDiscount" rel="tooltip" title="Global Discount" trigger data-placement="bottom" name="globalDiscount"></td>
      <td><input type="text" value="0" class="price table-input grandTotal" name="grandTotal" readonly></td>
    </tr>
@endif

<script type="text/javascript">
  $(function () {
    computeAll();
    $(".table-input").on('change', function () {
      var id = $(this).data('id');
      recompute(id);
    });

    $(".globalDiscount").on('change', function () {
      let value = parseInt($(this).val());
      if (value > 0 && value <= 100) {
        computeAll();
      }
    });

    $(".btn-remove-item").on('click', function () {
      var id = $(this).data('id');
      $.post("{!! route('remove-to-cart') !!}", {id: id}, (o) => {
        if (o.is_successful) {
          $.notify({
            icon: 'ti-trash',
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
      }, 'json');
    });
  });

  function updateCart (itemId) {
    if (!itemId) return
    var quantity  = $("#input-quantity-" + itemId).val();
    var discount  = $("#input-discount-" + itemId).val();
    var totalPrice  = $("#input-total-" + itemId).val();
    $.post("{!! route('update-cart') !!}", {itemId: itemId, quantity: quantity, discount: discount, totalPrice: totalPrice}, (o) => {
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

        $("#input-quantity-" + o.item.id).val(o.item.quantity);
        recompute(o.item.id);
      }
    }, 'json');
  }

  function recompute (id) {
    var qty      = $("#input-quantity-" + id).val();
    var price    = $("#input-price-" + id).val();
    var discount = $("#input-discount-" + id).val();
    var discountedPrice = 0;
    var total = 0;
    if (discount > 0) {
      discountedPrice = price - (price * (discount / 100));
      total = (qty * discountedPrice)
    } else {
      total = (qty * price)
    }

    $("#input-total-" + id).val(total);
    updateCart(id);
    computeAll();
  }

  function computeAll () {
    var discount = parseInt($(".globalDiscount").val());
    var totalQty = 0;
    var netSale  = 0;
    var grossSale = 0;
    $(".quantity-text").each(function (index) {
      var iQty = parseInt($(this).val());
      totalQty += iQty;
    });

    var totalAmountItems = 0;
    $(".total-amount-item").each(function (index) {
      var iTotal = parseFloat($(this).val());
      totalAmountItems += iTotal;
    });

    grossSale = totalAmountItems
    netSale = totalAmountItems
    if (discount > 0 && discount <= 100) {
      netSale = grossSale - (grossSale * (discount / 100))
    } else {
      discount = 0
    }

    $(".total-quantity").html(totalQty)
    $(".grandTotal").val(netSale.toFixed(2))

    if (totalQty <= 0) {
      $("#btn-finalize").attr('disabled', 'disabled');
    } else {
      $("#btn-finalize").removeAttr('disabled');
    }

    $.post("{!! route('update-order') !!}", {globalDiscount: discount, netPrice: netSale, grossPrice: grossSale, totalQuantity: totalQty}, function (o) {
      console.log(`[DEBUG] UPDATED`, o)
    }, 'json');
  }
</script>
