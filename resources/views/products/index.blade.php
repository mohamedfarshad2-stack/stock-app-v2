@extends('layouts.app')

@section('content')
@php
  // Use request values if controller didn't pass them explicitly
  $gender = $gender ?? request('gender', 'men');
  $q      = $q ?? request('q', '');
  $sizes  = $sizes ?? ($gender === 'women' ? [36,37,38,39,40,41] : [39,40,41,42,43,44,45]);
  $cutSel = request('cut', 'any'); // any | only | exclude (for download form)
@endphp


<div class="container">

  {{-- Flash messages --}}
  @if(session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if(session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <strong>Oops!</strong> Please fix the errors and try again.
    </div>
  @endif

  {{-- Men/Women tabs --}}
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link {{ $gender === 'men' ? 'active' : '' }}"
         href="{{ route('products.index', ['gender' => 'men']) }}">Men</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ $gender === 'women' ? 'active' : '' }}"
         href="{{ route('products.index', ['gender' => 'women']) }}">Women</a>
    </li>
  </ul>

  {{-- Toolbar: search + download + add --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 mb-3">

    {{-- Search --}}
    <form method="GET" class="d-flex gap-2 w-100 w-md-auto">
      <input type="hidden" name="gender" value="{{ $gender }}">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search item code or title">
      <button class="btn btn-outline-secondary">Search</button>
    </form>

    <div class="d-flex flex-column flex-md-row gap-2">

      {{-- Download images by size (with cut filter) --}}
      <form method="GET" action="{{ route('products.download-images') }}" class="d-flex gap-2">
        <input type="hidden" name="gender" value="{{ $gender }}">
        <select name="size" class="form-select">
          @foreach($sizes as $s)
            <option value="{{ $s }}">{{ $s }}</option>
          @endforeach
        </select>
        <select name="cut" class="form-select">
          <option value="any"    {{ $cutSel === 'any' ? 'selected' : '' }}>All slippers</option>
          <option value="only"   {{ $cutSel === 'only' ? 'selected' : '' }}>Only CUT slippers</option>
          <option value="exclude"{{ $cutSel === 'exclude' ? 'selected' : '' }}>Exclude CUT slippers</option>
        </select>
        <button class="btn btn-outline-dark">Download images</button>
      </form>

      {{-- Add product --}}
      <a class="btn btn-primary" href="{{ route('products.create', ['gender' => $gender]) }}">Add Product</a>
    </div>
  </div>

  {{-- Grid of products --}}
  <div class="row row-cols-1 row-cols-md-3 g-3">
    @forelse($products as $p)
      @php
        // Build a quick map of size => qty for badges
        $map = $p->sizes->pluck('quantity','size')->all();
      @endphp
      <div class="col">
        <div class="card h-100">
          {{-- Image --}}
          @if($p->image_path)
            <img class="card-img-top" src="{{ asset('storage/'.$p->image_path) }}" alt="{{ $p->title }}">
          @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:160px;">
              No Image
            </div>
          @endif

          {{-- Body --}}
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-semibold">{{ $p->item_code }}</div>
                <div class="small text-muted">{{ $p->title }}</div>
              </div>
              <div class="text-end">
                @if($p->is_cut)
                  <span class="badge bg-warning text-dark">CUT</span>
                @endif
                <div class="small text-muted">{{ ucfirst($p->gender) }}</div>
              </div>
            </div>

            <div class="small mt-2">
              <strong>Cost:</strong> {{ number_format($p->cost, 2) }}
            </div>
             <div class="small mt-2">
              
              <strong>Number Of Straps:</strong> {{ $p->strap?->quantity ?? 0 }}
            </div>

            {{-- Stock by size --}}
            <div class="mt-2">
              <div class="small text-muted mb-1">Stock by size</div>
              <div class="d-flex flex-wrap gap-2">
                @foreach($sizes as $s)
                  <span class="badge {{ ($map[$s] ?? 0) > 0 ? 'bg-success' : 'bg-secondary' }}">
                    {{ $s }}: {{ $map[$s] ?? 0 }}
                  </span>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Footer actions --}}
          <div class="card-footer d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('products.edit', $p) }}">Edit</a>
            <form method="POST" action="{{ route('products.destroy', $p) }}" onsubmit="return confirm('Delete this product?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col">
        <em class="text-muted">No products found.</em>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <!-- <div class="mt-3">
    {{ $products->links() }}
  </div> -->
  <div class="mt-3">
  {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

</div>
@endsection
