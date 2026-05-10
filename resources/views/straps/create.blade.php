@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">Add Strap</h1>

  <form method="POST" action="{{ route('straps.store') }}" enctype="multipart/form-data" class="card p-3">
    @csrf

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
      @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
      <div class="mb-3">
      <label class="form-label">Item Code</label>
      <input type="text" name="item_code" value="{{ old('item_code') }}" class="form-control" required>
      @error('item_code')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Quantity</label>
      <input type="number" name="quantity" value="{{ old('quantity', 0) }}" class="form-control" min="0" required>
      @error('quantity')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Image (optional)</label>
      <input type="file" name="image" class="form-control" accept="image/*">
      @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('straps.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
