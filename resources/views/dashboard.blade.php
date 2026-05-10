<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<h3>Customer Order Dashboard</h3>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card p-3">
            <h5>Total Orders</h5>
            <h2>{{ $total_orders }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h5>Delivered</h5>
            <h2>{{ $delivered }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h5>Returned</h5>
            <h2>{{ $returned }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h5>Error</h5>
            <h2>{{ $error }}</h2>
        </div>
    </div>
</div>

</body>
</html>
