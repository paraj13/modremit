<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Registration | Modremit</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<a href="/" class="brand-logo">MODREMIT</a>

<h4 class="fw-bold mb-2 text-center">Create Agent Account</h4>
<p class="text-muted small text-center mb-4">
Join our global network and start sending money worldwide.
</p>

@if ($errors->any())
<div class="alert alert-danger small">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('register') }}" method="POST">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label class="form-label small fw-bold">Full Name</label>
<input type="text" name="name"
class="form-control form-control-premium"
placeholder="John Doe"
value="{{ old('name') }}"
required>
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Email Address</label>
<input type="email" name="email"
class="form-control form-control-premium"
placeholder="john@example.com"
value="{{ old('email') }}"
required>
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Phone Number</label>
<input type="text" name="phone"
class="form-control form-control-premium"
placeholder="+41 71 123 45 67"
value="{{ old('phone') }}"
required>
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
<input type="password" name="password"
class="form-control form-control-premium"
required>
</div>

<div class="col-md-6">
<label class="form-label small fw-bold">Confirm Password</label>
<input type="password" name="password_confirmation"
class="form-control form-control-premium"
required>
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

</body>
</html>