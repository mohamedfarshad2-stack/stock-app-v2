<!DOCTYPE html>
<html>
<head>
    <title>Import Excel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<h3>Upload Customer Excel</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="file" name="file" class="form-control" required>
<button class="btn btn-primary mt-3">Upload</button>
</form>

</body>
</html>
