<table class="table table-hover table-sm">
  <thead class="thead-dark">
    <th>Medicine Name</th>
    <th>Quantity</th>
    <th>Supplier Price</th>
    <th>Total Price</th>
    <th>Net</th>
  </thead>
  <tbody>
    @if(count($sales))
      @foreach($sales as $key => $value)
        <tr>
          <td>{{ $value['productName'] }}</td>
          <td>{{ (int) $value['quantity'] }}</td>
          <td>&#8369; {{ number_format($value['supplierPrice'], 2, '.', ',') }}</td>
          <td>&#8369; {{ number_format($value['totalPrice'], 2, '.', ',') }}</td>
          <td>&#8369; {{ number_format($value['total'], 2, '.', ',') }}</td>
        </tr>
      @endforeach
      <tr>
        <td style="text-align: right">TOTAL:</td>
        <td>{{ $totalQty }}</td>
        <td>-</td>
        <td>&#8369; {{ number_format($sum, 2, '.', ',') }}</td>
        <td>-</td>
      </tr>
    @else
    <tr>
      <td colspan="5" style="text-align: center;">NO DATA AVAILABLE</td>
    </tr>
    @endif
  </tbody>
</table>
@if(count($sales))
<div>
  <a href="{!! route('report-download-date-range', ['startDate' => $fromDate, 'endDate' => $tillDate]) !!}" class="btn btn-block btn-success" target="_blank">DOWNLOAD</a>
</div>
@endif
