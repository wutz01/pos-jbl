@extends('templates.skeleton')
@section('location', 'My Profile')
@section('title', 'My Profile')
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
              <img class="avatar border-white" src="{{ asset('img/faces/face-1.jpg') }}" alt="..."/>
              <h4 class="title"><span id="label-name">{{ Auth::user()->name }}</span><br />
                 <a href="#"><small id="label-username">@ {{ Auth::user()->username }}</small></a>
              </h4>
            </div>
            <p class="description text-center">
                {{ Auth::user()->email }}
            </p>
        </div>
        <hr>
      </div>
    </div>
    <div class="col-lg-8 col-md-7">
      <div class="card">
        <div class="content">
          <div class="error-wrapper"></div>
          <form action="{!! route('update-profile') !!}" method="post" id="update-profile-form">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Name</label>
                          <input type="text" class="form-control border-input" name="name" placeholder="Name" value="{{ Auth::user()->name }}" required>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Username</label>
                          <input type="text" class="form-control border-input" name="username" placeholder="Username" value="{{ Auth::user()->username }}">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label>Email</label>
                          <input type="email" class="form-control border-input" name="email" placeholder="Email" value="{{ Auth::user()->email }}" required>
                      </div>
                  </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control border-input" name="password" placeholder="Password" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control border-input" name="confirm_password" placeholder="Confirm Password" value="">
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
    $(".side-nav-profile").addClass("active");

    $("#update-profile-form").validate();
    $("#update-profile-form").ajaxForm({
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
</script>
@endsection
