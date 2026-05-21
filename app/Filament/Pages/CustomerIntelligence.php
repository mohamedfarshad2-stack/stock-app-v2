<?php

namespace App\Filament\Pages;

use App\Services\CustomerIntelligenceService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use App\Models\SearchLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerIntelligence extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Customer Intelligence';
    protected static ?string $navigationGroup = 'Legacy';
    protected static ?int $navigationSort = 999;
    protected static string $view = 'filament.pages.customer-intelligence';

    public ?string $search = null;
    public ?array $result = null;

    public function mount(): void
    {
        $this->form->fill([
            'search' => '',
        ]);
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

 protected function getFormSchema(): array
{
    return [
        Forms\Components\Section::make('Search Customer')
            ->schema([
                Forms\Components\TextInput::make('search')
                    ->label('Phone / Alternate Phone / WhatsApp')
                    ->placeholder('0771234567 / 94771234567')
                    ->extraAttributes([
        'wire:keydown.enter.prevent' => 'searchCustomer'
    ])
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('search')
                            ->icon('heroicon-o-search')
                            ->tooltip('Search')
                            ->action(fn () => $this->searchCustomer())
                            ->extraAttributes(['type' => 'button']) // 🔥 prevents validation
                    ),
            ])
            ->columns(1),
    ];
}

//     public function searchCustomer(): void
// {
//     $search = $this->form->getState()['search'] ?? null;

//     if (! $search || trim($search) === '') {
//         $this->result = null;
//         return;
//     }

//     $service = app(\App\Services\CustomerIntelligenceService::class);
//     $this->result = $service->build($search);

//     // ✅ Normalize phone
//     $normalized = Customer::normalizePhone($search);

//     // ✅ Log search
//     SearchLog::create([
//         'user_id' => Auth::id(),
//         'searched_phone' => $search,
//         'normalized_phone' => $normalized,

//         'result_count' => $this->result ? count($this->result['orders']) : 0,
//         'found' => $this->result ? true : false,

//         'delivery_probability' => $this->result['prediction']['delivery_probability'] ?? null,
//         'risk_level' => $this->result['risk']['risk_level'] ?? null,

//         'search_type' => 'manual',
//     ]);
// }
public function searchCustomer(): void
{
    $user = Auth::user();

    $search = $this->form->getState()['search'] ?? null;

    if (! $search || trim($search) === '') {
        $this->result = null;
        return;
    }

    // ❌ BLOCK IF NO CONNECTS
    if ($user->role != 1 && $user->connects <= 0) {
        $this->result = null;

        $this->notify('danger', 'No connects remaining.');
        return;
    }

    $service = app(\App\Services\CustomerIntelligenceService::class);
    $this->result = $service->build($search);

    $normalized = Customer::normalizePhone($search);

    // ❌ NO RESULT → NO DEDUCTION
    if (!$this->result) {
        SearchLog::create([
            'user_id' => $user->id,
            'searched_phone' => $search,
            'normalized_phone' => $normalized,
            'result_count' => 0,
            'found' => false,
            'search_type' => 'manual',
        ]);

        return;
    }

    $orderCount = count($this->result['orders']);

    // 🔥 CONNECT DEDUCTION LOGIC
    $deduction = $orderCount > 3 ? 3 : 1;

    // Admin bypass
    if ($user->role != 1) {
        $user->connects = max(0, $user->connects - $deduction);
        $user->save();
    }

    // ✅ LOG
    SearchLog::create([
        'user_id' => $user->id,
        'searched_phone' => $search,
        'normalized_phone' => $normalized,
        'result_count' => $orderCount,
        'found' => true,
        'delivery_probability' => $this->result['prediction']['delivery_probability'] ?? null,
        'risk_level' => $this->result['risk']['risk_level'] ?? null,
        'search_type' => 'manual',
    ]);
}

    public function getTitle(): string
    {
        return 'Customer Intelligence';
    }
}
