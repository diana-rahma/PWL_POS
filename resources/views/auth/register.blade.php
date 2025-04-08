<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat akun Pengguna</title>
     <!-- Google Font: Source Sans Pro --> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> 
    <!-- Font Awesome --> 
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}"> 
    <!-- icheck bootstrap --> 
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> 
    <!-- SweetAlert2 --> 
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}"> 
    <!-- Theme style --> 
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}"> 
</head>
<body class="hold-transition register-page">
    <div class="register-box">
        <!-- /.register-logo -->
        <div class="card card-outline card-primary">
          <div class="card-header text-center">
            <a href="{{ url('/login') }}" class="h1"><b>Admin</b>LTE</a>
          </div>
          <div class="card-body">
            <p class="register-box-msg">Buat akun anda</p>
      
            <form action="{{ url('register_proses') }}" method="POST" id="form-register">
              @csrf
      
              <div class="input-group mb-3">
                <input  type="text" id="username" name="username" class="form-control" placeholder="Username">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              <small id="error-username" class="error-text text-danger"></small>

              <div class="input-group mb-3">
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <small id="error-nama" class="error-text text-danger"></small>
      
              <div class="input-group mb-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <small id="error-password" class="error-text text-danger"></small>

              <div class="form-group">
                <select name="level_id" id="level_id" class="form-control" required >
                    <option value="">- Pilih Level -</option>
                    <option value="1">ADMIN</option>
                    <option value="2">MANAGER</option>
                    <option value="3">STAFF</option>
                </select>
                <small id="error-level_id" class="error-text form-text text-danger"></small>
            </div>
      
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block">Buat Akun</button>
                </div>
                <!-- /.col -->
              </div>
              
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.register-box -->
       
    <!-- jQuery --> 
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script> 
    <!-- Bootstrap 4 --> 
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
    <!-- jquery-validation --> 
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script> 
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script> 
    <!-- SweetAlert2 --> 
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script> 
    <!-- AdminLTE App --> 
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script> 
 
<script> 
    $.ajaxSetup({ 
    headers: { 
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
    } 
    }); 

    $(document).ready(function() { 
    $("#form-register").validate({ 
        rules: { 
        username: {required: true, minlength: 4, maxlength: 20}, 
        nama: {required: true, minlength: 4, maxlength: 20}, 
        password: {required: true, minlength: 6, maxlength: 20},
        level_id: {required: true, minlength: 1, maxlength: 2}
        }, 
        submitHandler: function(form) {  
        $.ajax({ 
            url: form.action, 
            type: form.method, 
            data: $(form).serialize(), 
            success: function(response) { 
            if(response.status){ 
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: response.message, 
                }).then(function() { 
                    window.location = response.redirect; 
                }); 
            }else{  
                $('.error-text').text(''); 
                $.each(response.msgField, function(prefix, val) { 
                    $('#error-'+prefix).text(val[0]); 
                }); 
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Terjadi Kesalahan', 
                    text: response.message 
                }); 
            } 
            } 
        }); 
        return false; 
        }, 
        errorElement: 'span', 
        errorPlacement: function (error, element) { 
        error.addClass('invalid-feedback'); 
        element.closest('.input-group').append(error); 
        }, 
        highlight: function (element, errorClass, validClass) { 
        $(element).addClass('is-invalid'); 
        }, 
        unhighlight: function (element, errorClass, validClass) { 
        $(element).removeClass('is-invalid'); 
        } 
    }); 
    }); 
</script>
</body>
</html>