<!DOCTYPE html>
<html>
<head>
<title>Search Customer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">
<h3>Search Customer</h3>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('search.result') }}" method="GET">
<input type="text" name="q" class="form-control" placeholder="Enter phone or name">
<button class="btn btn-success mt-3">Search</button>
</form>

</body>
</html>
