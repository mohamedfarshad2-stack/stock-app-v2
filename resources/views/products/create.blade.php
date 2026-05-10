@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">Add Product ({{ ucfirst($gender) }})</h1>

  <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="card p-3">
    @csrf
    <input type="hidden" name="gender" value="{{ $gender }}">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Item Code</label>
        <input name="item_code" value="{{ old('item_code') }}" class="form-control" required>
        @error('item_code')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input name="title" value="{{ old('title') }}" class="form-control" required>
        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Cost</label>
        <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost',0) }}" class="form-control" required>
        @error('cost')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3 d-flex align-items-end">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="is_cut" value="1" id="is_cut">
    <label class="form-check-label" for="is_cut">Mark slipper as Cut Size</label>
  </div>
</div>
    </div>

    <hr>
    <h6>Stock by Size</h6>
    <div class="row g-2">
      @foreach($sizes as $s)
        <div class="col-6 col-md-3">
          <label class="form-label small">Size {{ $s }}</label>
          <input type="number" min="0" name="sizes[{{ $s }}]" value="{{ old("sizes.$s", 0) }}" class="form-control">
        </div>
      @endforeach
    </div>

    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('products.index',['gender'=>$gender]) }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
