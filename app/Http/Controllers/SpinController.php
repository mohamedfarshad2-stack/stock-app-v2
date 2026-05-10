<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Spin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SpinController extends Controller
{
    public function showForm()
    {
        return view('spin.form');
    }

    public function startSpin(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $rewards = $this->getRewards($request->phone);

        session([
            'customer_name' => $request->name,
            'customer_phone'=> $request->phone,
            'rewards'       => $rewards,
        ]);

        return redirect()->route('spin.result');
    }

    public function showResult()
    {
        // If you want, you can also pass rewards to the view here.
        return view('spin.result');
    }

    /**
     * Spin API:
     * - Enforces 2 spins/day AND both-unutilized rule
     * - Creates a new Spin on success
     * - Returns: blocked, message, reward, rewards (latest list), remaining_spins
     */
    public function apiSpin(Request $request)
    {
        $fullName = session('customer_name');
        $phone    = session('customer_phone');

        if (!$phone) {
            return response()->json([
                'blocked' => true,
                'message' => '⚠️ No session phone detected. Please log in or start a session.',
                'rewards' => [],
            ], 400);
        }

        // Today’s stats
        $todaySpins = Spin::where('phone', $phone)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $unutilizedSpins = Spin::where('phone', $phone)
            ->whereDate('created_at', Carbon::today())
            ->where('utilized', false)
            ->count();

        // Block if already spun twice today AND both are still unutilized
        if ($todaySpins >= 2 && $unutilizedSpins >= 2) {
            $rewardsList = $this->getRewards($phone);

            // Also keep session in sync for your Blade hydration
            session(['rewards' => $rewardsList]);

            return response()->json([
                'blocked'         => true,
                'message'         => '❌ You have used both spins for today! Please utilize at least one reward before spinning again.',
                'rewards'         => $rewardsList,
                'remaining_spins' => 0,
            ]);
        }

        // Build initials and reward pool
        $initials = strtoupper(substr((string)$fullName, 0, 2));

        $pool = [
            'LKR 1000 Rupees',
            'LKR 500 Rupees',
            'Free Delivery',
            'Try Again',
            'LKR 500 Rupees',
            'LKR 1000 Rupees',
            '25% OFF Coupon',
            'Try Again',
        ];

        $reward = $pool[array_rand($pool)];

        // Persist the spin (utilized defaults to false)
        Spin::create([
            'name'   => $fullName,
            'phone'  => $phone,
            'reward' => $reward,
        ]);

        // Fresh list after inserting
        $latestList = $this->getRewards($phone);

        // Keep session in sync for the page
        session(['rewards' => $latestList]);

        return response()->json([
            'blocked'         => false,
            'reward'          => $reward,
            'rewards'         => $latestList,
            'remaining_spins' => max(0, 2 - ($todaySpins + 1)), // after this spin
        ]);
    }

    /**
     * Fetch the latest rewards for a phone (limit 30 for performance).
     */
    private function getRewards(string $phone)
    {
        return Spin::where('phone', $phone)
            ->latest()
            ->take(30)
            ->get(['id','reward','utilized','created_at']);
    }
public function showWholesaleForm()
{
    // dd("D");
return view('wholesale');
}


public function submit(Request $request)
{
$data = $request->validate([
'name' => 'required|string|max:120',
'business_name' => 'required|string|max:160',
'phone' => 'required|string|max:40',
'whatsapp' => 'nullable|string|max:40',
'email' => 'nullable|email|max:160',
'location' => 'required|string|max:120',
'monthly_volume'=> 'nullable|string|max:100',
'interests' => 'nullable|string|max:255', // comma-joined from checkboxes
'message' => 'nullable|string|max:2000',
'hp' => 'nullable|string|max:0', // simple honeypot
], [
'hp.max' => 'Bot detected.'
]);

unset($data['hp']); // 🔑 remove honeypot before insert
// Persist
$data['id'] = Str::uuid()->toString();
$data['created_at'] = now();
$data['updated_at'] = now();
DB::table('wholesale_inquiries')->insert($data);


// Notify (optional but handy)
try {
$to = config('mail.wholesale_alert_to', env('WHOLESALE_ALERT_TO'));
if ($to) {
Mail::send([], [], function ($message) use ($to, $data) {
$message->to($to)
->subject('New Wholesale Inquiry — ' . ($data['business_name'] ?? $data['name']))
->setBody(view('emails.wholesale-inquiry', compact('data'))->render(), 'text/html');
});
}
} catch (\Throwable $e) {
// swallow silently; we still accept the lead
report($e);
}


return redirect()->route('wholesale.show')->with('ok', 'Thanks! We\'ll reach out within 24 hours.');
}
    
}
