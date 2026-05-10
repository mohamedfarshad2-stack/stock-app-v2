<?php
namespace App\Http\Controllers;

use App\Models\Strap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StrapController extends Controller
{
//     public function index(Request $request)
//     {
//         $q = $request->get('q');

//         $straps = Strap::query()
//             ->when($q, function ($query) use ($q) {
//                 $query->where('item_code', 'like', "%{$q}%");
//             })
//             ->orderByDesc('created_at')
//             ->paginate(12)
//             ->withQueryString();
// // dd($straps);
//         return view('straps.index', compact('straps', 'q'));
//     }
public function index(Request $request)
{
    $q = $request->get('q');

    $straps = Strap::query()
       ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->whereRaw('item_code ILIKE ?', ["%{$q}%"])
                    ->orWhereRaw('title ILIKE ?', ["%{$q}%"]);
            });
        })
        ->orderByDesc('created_at')
        ->paginate(25)
        ->withQueryString();

    return view('straps.index', compact('straps', 'q'));
}

    public function create()
    {
        return view('straps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['string','max:100'],
            'item_code' => ['required','string','max:100','unique:straps,item_code'],
            'quantity'  => ['required','integer','min:0'],
            'image'     => ['nullable','image','max:2048'], // 2MB
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            // store in storage/app/public/straps
            $path = $request->file('image')->store('straps', 'public');
        }

        Strap::create([
            'title' => $validated['title'],
            'item_code' => $validated['item_code'],
            'quantity'  => $validated['quantity'],
            'image_path'=> $path,
        ]);

        return redirect()->route('straps.index')->with('ok', 'Strap created.');
    }

    public function edit(Strap $strap)
    {
        return view('straps.edit', compact('strap'));
    }

    public function update(Request $request, Strap $strap)
    {
        $validated = $request->validate([
            'title' => ['string','max:100'],
            'item_code' => ['required','string','max:100', Rule::unique('straps','item_code')->ignore($strap->id)],
            'quantity'  => ['required','integer','min:0'],
            'image'     => ['nullable','image'],
        ]);

        if ($request->hasFile('image')) {
            if ($strap->image_path) {
                Storage::disk('public')->delete($strap->image_path);
            }
            $strap->image_path = $request->file('image')->store('straps', 'public');
        }

        $strap->title = $validated['title'];
        $strap->item_code = $validated['item_code'];
        $strap->quantity  = $validated['quantity'];
        $strap->save();

        return redirect()->route('straps.index')->with('ok', 'Strap updated.');
    }

    public function destroy(Strap $strap)
    {
        if ($strap->image_path) {
            Storage::disk('public')->delete($strap->image_path);
        }
        $strap->delete();

        return back()->with('ok', 'Strap deleted.');
    }
}
