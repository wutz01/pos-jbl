<ul class="list-unstyled team-members">
  @if(count($stock))
    @foreach($stock as $value)
    <li>
      <div class="row">
        <div class="col-xs-2">
          @if($value->type == "ADD")
          <span class="fa fa-plus-square fa-3x"></span>
          @elseif($value->type == "UPDATE")
          <span class="fa fa-edit fa-3x"></span>
          @elseif($value->type == "ARCHIVE" || $value->type == "REMOVED")
          <span class="fa fa-trash fa-3x"></span>
          @elseif($value->type == "RETURNED")
          <span class="fa fa-undo fa-3x"></span>
          @elseif($value->type == "ORDERED")
          <span class="fa fa-credit-card fa-3x"></span>
          @else
          <span class="fa fa-trash fa-3x"></span>
          @endif
        </div>
        <div class="col-xs-10">
          {{ $value->userAttached->name }} {!! $value->message !!}
          <br />
          <span class="text-muted"><small>{{ Carbon\Carbon::parse($value->created_at)->toDateTimeString() }}</small></span>
        </div>
      </div>
    </li>
    @endforeach
  @else
    <li>
      <div class="row">
        <div class="col-xs-2">
          <span class="fa fa-times fa-3x"></span>
        </div>
        <div class="col-xs-10">
          <h5>No Trails found.</h5>
        </div>
      </div>
    </li>
  @endif
  <!-- <li>
    <div class="row">
      <div class="col-xs-2">
        <span class="fa fa-edit fa-3x"></span>
      </div>
      <div class="col-xs-10">
        Admin has updated our inventory.
        has added <strong>10</strong> pcs in our inventory.
        <br />
        <span class="text-muted"><small>time here</small></span>
      </div>
    </div>
  </li>
  <li>
    <div class="row">
      <div class="col-xs-2">
        <span class="fa fa-trash fa-3x"></span>
      </div>
      <div class="col-xs-10">
        Admin has archived our inventory.
        <br />
        <span class="text-muted"><small>time here</small></span>
      </div>
    </div>
  </li> -->
</ul>
