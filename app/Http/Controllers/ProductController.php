<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use ZipArchive;

class ProductController extends Controller
{
    // Men/Women sizes
    private array $MEN_SIZES   = [39,40,41,42,43,44,45];
    private array $WOMEN_SIZES = [36,37,38,39,40,41];

    public function index(Request $request)
    {
        $gender = $request->get('gender', 'men'); // default men
        $q      = $request->get('q');

        $products = Product::with('sizes','strap')
            ->where('gender', $gender)
            ->when($q, function($query) use ($q) {
                // Use ILIKE for Postgres; fallback to like for MySQL
                $driver = DB::getDriverName();
                if ($driver === 'pgsql') {
                    $query->where(function($sub) use ($q) {
                        $sub->whereRaw('item_code ILIKE ?', ["%{$q}%"])
                            ->orWhereRaw('title ILIKE ?', ["%{$q}%"]);
                    });
                } else {
                    $query->where(function($sub) use ($q) {
                        $sub->where('item_code', 'like', "%{$q}%")
                            ->orWhere('title', 'like', "%{$q}%");
                    });
                }
            })
            ->orderByDesc('updated_at')
            ->paginate(25)
            ->withQueryString();

        $sizes = $gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;

        return view('products.index', compact('products','gender','q','sizes'));
    }

    public function create(Request $request)
    {
        $gender = $request->get('gender', 'men');
        $sizes  = $gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;
        return view('products.create', compact('gender','sizes'));
    }

    public function store(Request $request)
    {
        $gender = $request->input('gender', 'men');
        $sizes  = $gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;

        $validated = $request->validate([
        'gender' => ['required', Rule::in(['men','women'])],
        'item_code' => ['required','string','max:100','unique:products,item_code'],
        'title' => ['required','string','max:255'],
        'cost' => ['required','numeric','min:0'],
        'image' => ['nullable','image','max:4096'],
        'is_cut' => ['nullable','boolean'],
        ]);

        DB::transaction(function() use ($request, $sizes) {
            $path = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
            'gender'     => $request->gender,
            'item_code'  => $request->item_code,
            'title'      => $request->title,
            'cost'       => $request->cost,
            'image_path' => $path,
            'is_cut'     => (bool) $request->boolean('is_cut'),
            ]);

            foreach ($sizes as $size) {
                $qty = (int) data_get($request, "sizes.$size", 0);
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => $size,
                    'quantity'   => max(0, $qty),
                ]);
            }
        });

        return redirect()->route('products.index', ['gender' => $gender])
            ->with('ok', 'Product created.');
    }

    public function edit(Product $product)
    {
        $sizes = $product->gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;
        $sizeMap = $product->sizes()->pluck('quantity','size')->all();
        return view('products.edit', compact('product','sizes','sizeMap'));
    }

    public function update(Request $request, Product $product)
    {
        $sizes  = $product->gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;

       $validated = $request->validate([
  'item_code' => ['required','string','max:100', Rule::unique('products','item_code')->ignore($product->id)],
  'title'     => ['required','string','max:255'],
  'cost'      => ['required','numeric','min:0'],
  'image'     => ['nullable','image','max:4096'],
  'is_cut'    => ['nullable','boolean'],
]);

        DB::transaction(function() use ($request, $product, $sizes) {
            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $product->image_path = $request->file('image')->store('products', 'public');
            }

            $product->item_code = $request->item_code;
            $product->title     = $request->title;
            $product->cost      = $request->cost;
            $product->is_cut   = (bool) $request->boolean('is_cut');
            $product->save();

            foreach ($sizes as $size) {
                $qty = (int) data_get($request, "sizes.$size", 0);
                $row = $product->sizes()->firstOrNew(['size' => $size]);
                $row->quantity = max(0, $qty);
                $row->save();
                $product->touch();

            }
        });

        return redirect()->route('products.index', ['gender' => $product->gender])
            ->with('ok', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return back()->with('ok','Product deleted.');
    }

    /**
     * Download a ZIP of product images for a given gender+size,
     * including only products where quantity(size) > 0.
     * GET /products/download-images?gender=men&size=41
     */
    // public function downloadImages(Request $request)
    // {
    //     $validated = $request->validate([
    //         'gender' => ['required', Rule::in(['men','women'])],
    //         'size'   => ['required','integer'],
    //     ]);

    //     $gender = $validated['gender'];
    //     $size   = (int) $validated['size'];

    //     // Validate size is allowed for that gender
    //     $allowed = $gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;
    //     abort_unless(in_array($size, $allowed, true), 422, 'Invalid size for selected gender.');

    //     $products = Product::with(['sizes' => function($q) use ($size) {
    //             $q->where('size', $size)->where('quantity','>',0);
    //         }])
    //         ->where('gender', $gender)
    //         ->whereHas('sizes', fn($q) => $q->where('size',$size)->where('quantity','>',0))
    //         ->get();

    //     if ($products->isEmpty()) {
    //         return back()->with('warn', 'No images found for that size with stock > 0.');
    //     }

    //     $zip = new ZipArchive();
    //     $fileName = "images_{$gender}_size{$size}_" . now()->format('Ymd_His') . ".zip";
    //     $zipPath = storage_path("app/tmp/{$fileName}");
    //     if (!is_dir(dirname($zipPath))) {
    //         mkdir(dirname($zipPath), 0775, true);
    //     }

    //     if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    //         abort(500, 'Cannot create ZIP file');
    //     }

    //     foreach ($products as $p) {
    //         if (!$p->image_path) continue;
    //         $full = Storage::disk('public')->path($p->image_path);
    //         if (!is_file($full)) continue;

    //         // Friendly filename: ITEMCODE_TITLE_sizeNN.ext
    //         $ext = pathinfo($full, PATHINFO_EXTENSION);
    //         $safeTitle = preg_replace('/[^A-Za-z0-9_\-]+/','-', $p->title);
    //         $nameInZip = "{$p->item_code}_{$safeTitle}_size{$size}.{$ext}";

    //         $zip->addFile($full, $nameInZip);
    //     }

    //     $zip->close();

    //     return response()->download($zipPath)->deleteFileAfterSend(true);
    // }
    public function downloadImages(Request $request)
{
    $validated = $request->validate([
        'gender' => ['required', Rule::in(['men','women'])],
        'size'   => ['required','integer'],
        'cut'    => ['nullable', Rule::in(['any','only','exclude'])], // product-level
    ]);

    $gender = $validated['gender'];
    $size   = (int) $validated['size'];
    $cut    = $validated['cut'] ?? 'any';

    $allowed = $gender === 'men' ? $this->MEN_SIZES : $this->WOMEN_SIZES;
    abort_unless(in_array($size, $allowed, true), 422, 'Invalid size for selected gender.');

    $products = Product::where('gender', $gender)
        ->when($cut === 'only', fn($q) => $q->where('is_cut', true))
        ->when($cut === 'exclude', fn($q) => $q->where('is_cut', false))
        ->whereHas('sizes', fn($q) => $q->where('size', $size)->where('quantity','>',0))
        ->with(['sizes' => fn($q) => $q->where('size', $size)->where('quantity','>',0)])
        ->get();

    if ($products->isEmpty()) {
        return back()->with('warn', 'No images found for that filter (stock > 0).');
    }

    $zip = new \ZipArchive();
    $fileName = "images_{$gender}_size{$size}_{$cut}_" . now()->format('Ymd_His') . ".zip";
    $zipPath = storage_path("app/tmp/{$fileName}");
    if (!is_dir(dirname($zipPath))) mkdir(dirname($zipPath), 0775, true);
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) abort(500, 'Cannot create ZIP file');

    foreach ($products as $p) {
        if (!$p->image_path) continue;
        $full = Storage::disk('public')->path($p->image_path);
        if (!is_file($full)) continue;

        $ext = pathinfo($full, PATHINFO_EXTENSION);
        $safeTitle = preg_replace('/[^A-Za-z0-9_\-]+/','-', $p->title);
        $cutTag = $p->is_cut ? '_CUT' : '';
        $nameInZip = "{$p->item_code}_{$safeTitle}_size{$size}{$cutTag}.{$ext}";
        $zip->addFile($full, $nameInZip);
    }
    $zip->close();

    return response()->download($zipPath)->deleteFileAfterSend(true);
}

}
