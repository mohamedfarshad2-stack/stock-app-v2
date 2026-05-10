@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
    <title>Strap Stocks</title>
</head>
<body>
@section('content')
<div class="container">

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Strap Stocks</h1>
    <a href="{{ route('straps.create') }}" class="btn btn-primary">Add Strap</a>
  </div>

  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search by item code...">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  <div class="row row-cols-1 row-cols-md-3 g-3">
    @forelse($straps as $strap)
      <div class="col">
        <div class="card h-100">
          @if($strap->image_path)
            <img class="card-img-top" src="{{ asset('storage/'.$strap->image_path) }}" alt="{{ $strap->item_code }}">
          @else
            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:160px;">No Image</div>
          @endif
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <strong>{{ $strap->item_code }}</strong>
              <span class="badge bg-dark">Qty: {{ $strap->quantity }}</span>
            </div>
          </div>
          <div class="card-footer d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('straps.edit', $strap) }}">Edit</a>
            <form method="POST" action="{{ route('straps.destroy', $strap) }}" onsubmit="return confirm('Delete this strap?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col"><p class="text-muted">No straps found.</p></div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $straps->links() }}
  </div>
</div>
</body>
</html>
@endsection
