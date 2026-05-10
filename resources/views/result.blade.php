<!DOCTYPE html>
<html>
<head>
<title>Customer Result</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<h3>Customer Summary</h3>

<!-- <p><strong>Name:</strong> {{ $lead->name }}</p> -->
<p><strong>Phone:</strong> {{ $lead->phone }}</p>

<h4>Order Stats</h4>
<ul>
  <li>Total Orders: {{ $total }}</li>
  <li>Delivered (D): {{ $D }}</li>
  <li>Returned (R): {{ $R }}</li>
  <li>Exchange (E): {{ $E }}</li>
</ul>

<a href="/search" class="btn btn-secondary">Back</a>

</body>
</html>
