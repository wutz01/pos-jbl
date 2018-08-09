@extends('templates.skeleton')
@section('location', 'Error 403')
@section('title', 'Error 403')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">You are not allowed to view this page.</h4>
                    <!-- <p class="category">24 Hours performance</p> -->
                </div>
                <div class="content">
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
  })
</script>
@endsection
