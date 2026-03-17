<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Registration | Modremit</title>

<link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('css/brand.css') }}" rel="stylesheet">

<style>
body{
    font-family: 'Inter', sans-serif;
    background: var(--brand-lime);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.register-card{
    background:var(--white);
    border-radius:28px;
    padding:45px;
    width:100%;
    max-width:700px;
    box-shadow:0 25px 70px rgba(0,0,0,0.12);
}

.brand-logo{
    font-weight:800;
    font-size:1.8rem;
    letter-spacing:-1px;
    color:var(--brand-dark);
    text-align:center;
    display:block;
    margin-bottom:25px;
    text-decoration:none;
}

.form-control-premium{
    border-radius:12px;
    padding:12px 14px;
    border:1px solid #e2e8f0;
}

.form-control-premium:focus{
    border-color:var(--brand-dark);
    box-shadow:none;
}

.btn-brand{
    background:var(--brand-dark);
    color:white;
    border-radius:12px;
    font-weight:600;
}

.btn-brand:hover{
    background:#1b260a;
}
</style>
</head>

<body>

<div class="register-card">

<div class="mb-4 d-flex justify-content-center">
    <x-logo />
</div>

<h4 class="fw-bold mb-2 text-center">Create Agent Account</h4>
<p class="text-muted small text-center mb-4">
Join our global network and start sending money worldwide.
</p>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Validation Error',
            html: '{!! implode("<br>", $errors->all()) !!}',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: { popup: 'rounded-4 shadow-sm border-0' }
        });
    });
</script>
@endif

<form action="{{ route('register') }}" method="POST" id="registerForm">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label class="form-label small fw-bold">Full Name</label>
<input type="text" name="name"
class="form-control form-control-premium"
placeholder="John Doe"
value="{{ old('name') }}">
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Email Address</label>
<input type="email" name="email"
class="form-control form-control-premium"
placeholder="john@example.com"
value="{{ old('email') }}">
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Phone Number</label>
<input type="text" name="phone"
class="form-control form-control-premium"
placeholder="+41 71 123 45 67"
value="{{ old('phone') }}">
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Country</label>
<select name="country" class="form-control form-control-premium">
<option value="">Select Country</option>
<option>Switzerland</option>
<option>Germany</option>
<option>India</option>
<option>United Kingdom</option>
</select>
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Password</label>
<input type="password" name="password" id="password"
class="form-control form-control-premium">
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Confirm Password</label>
<input type="password" name="password_confirmation"
class="form-control form-control-premium">
</div>

</div>

<button type="submit"
class="btn btn-brand w-100 py-3 mt-4">
Submit Application
</button>

<div class="text-center mt-4">
<p class="small text-muted mb-0">
Already have an account?
<a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color:var(--brand-dark);">
Login here
</a>
</p>
</div>

</form>

</div>

<script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if ($.validator) {
            // Custom phone rule
            $.validator.addMethod("phoneFormat", function(value, element) {
                return this.optional(element) || /^\+?[0-9\s\-()]{7,20}$/.test(value);
            }, "Please enter a valid phone number.");

            $("#registerForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        phoneFormat: true
                    },
                    country: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    const btn = $(form).find('button[type="submit"]');
                    btn.addClass('btn-loading');
                    if (btn.hasClass('btn-light') || btn.hasClass('btn-brand-mint')) {
                        btn.addClass('btn-loading-dark');
                    }
                    form.submit();
                }
            });
        }
    });
</script>
</body>
</html>