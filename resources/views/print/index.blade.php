<!DOCTYPE html>
<html>
<head>
  <title>Invoice Import & List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css"/>
</head>
<body>
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Horns England — Invoices</h4>
    <div>
      <a href="{{ route('export') }}" class="btn btn-outline-primary btn-sm">Export</a>
      <a href="{{ route('bulk') }}" class="btn btn-secondary btn-sm" target="_blank">Print (Bulk)</a>
      <a href="{{ route('delete') }}" class="btn btn-outline-danger btn-sm"
         onclick="return confirm('Clear all imported rows?')">Clear All</a>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
    @csrf
    <div class="form-row">
      <div class="col">
        <input type="file" name="file" class="form-control form-control-sm" required>
        @error('file')<small class="text-danger">{{ $message }}</small>@enderror
      </div>
      <div class="col-auto">
        <button class="btn btn-success btn-sm">Import</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm table-striped table-hover">
      <thead class="thead-light">
      <tr>
        <th>#</th>
        <th>Tracking</th>
        <th>Name</th>
        <th>Address</th>
        <th>District</th>
        <th>Phone</th>
        <th>Item Code</th>
        <th class="text-right">Price</th>
        <th>Note</th>
      </tr>
      </thead>
      <tbody>
      @forelse($items as $i => $row)
        <tr>
          <td>{{ $items->firstItem() + $i }}</td>
          <td>{{ $row->tracking_number }}</td>
          <td>{{ $row->name }}</td>
          <td>{{ $row->address }}</td>
          <td>{{ $row->district }}</td>
          <td>{{ $row->phone_number }}</td>
          <td>{{ $row->item_code }}</td>
          <td class="text-right">{{ number_format($row->price,2) }}</td>
          <td>{{ $row->note }}</td>
        </tr>
      @empty
        <tr><td colspan="9" class="text-muted">No data yet. Import a file to begin.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
</body>
</html>
