<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoneyRecordResource\Pages;
use App\Models\BusinessUnit;
use App\Models\FinanceCategory;
use App\Models\MoneyRecord;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class MoneyRecordResource extends Resource
{
    protected static ?string $model = MoneyRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Transactions';

    private const TYPE_OPTIONS = [
        'income' => 'Income',
        'expense' => 'Direct / Overhead Cost',
        'transfer' => 'Transfer',
        'receivable' => 'Receivable',
        'payable' => 'Payable',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('business_unit_id')->options(BusinessUnit::query()->pluck('name', 'id'))->searchable()->required(),
            Forms\Components\DatePicker::make('record_date')->default(now())->required(),
            Forms\Components\Select::make('type')
                ->options(self::TYPE_OPTIONS)
                ->default('expense')
                ->required()
                ->reactive(),
            Forms\Components\Select::make('finance_category_id')
                ->label('Finance Category')
                ->options(function (callable $get) {
                    $type = (string) ($get('type') ?? 'expense');

                    return FinanceCategory::query()
                        ->where('is_active', true)
                        ->when(
                            $type !== '',
                            fn ($q) => $q->where('type', $type)
                        )
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->required()
                ->helperText('Categories are filtered by selected transaction type for simpler finance entry.'),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\TextInput::make('payment_method'),
            Forms\Components\TextInput::make('reference_no'),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('status')->options([
                'draft' => 'Draft','approved' => 'Approved','rejected' => 'Rejected',
            ])->default('approved')->required(),
            Forms\Components\FileUpload::make('attachment_path')->directory('money-records')->label('Attachment'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('record_date')->date()->sortable(),
            Tables\Columns\TextColumn::make('businessUnit.name')->label('Business Unit')->searchable(),
            Tables\Columns\TextColumn::make('financeCategory.name')->label('Category')->searchable(),
            Tables\Columns\BadgeColumn::make('type'),
            Tables\Columns\TextColumn::make('amount')->money('LKR')->sortable(),
            Tables\Columns\TextColumn::make('payment_method'),
            Tables\Columns\BadgeColumn::make('status'),
            Tables\Columns\TextColumn::make('user.name')->label('User'),
        ])->filters([
            Tables\Filters\SelectFilter::make('business_unit_id')->label('Business Unit')->relationship('businessUnit', 'name'),
            Tables\Filters\SelectFilter::make('finance_category_id')->label('Category')->relationship('financeCategory', 'name'),
            Tables\Filters\SelectFilter::make('type')->options(self::TYPE_OPTIONS),
            Tables\Filters\SelectFilter::make('status')->options([
                'draft' => 'Draft','approved' => 'Approved','rejected' => 'Rejected',
            ]),
            Tables\Filters\Filter::make('record_date')->form([
                Forms\Components\DatePicker::make('from'),
                Forms\Components\DatePicker::make('until'),
            ])->query(function ($query, array $data) {
                return $query
                    ->when($data['from'], fn ($q, $date) => $q->whereDate('record_date', '>=', $date))
                    ->when($data['until'], fn ($q, $date) => $q->whereDate('record_date', '<=', $date));
            }),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMoneyRecords::route('/'),
            'create' => Pages\CreateMoneyRecord::route('/create'),
            'edit' => Pages\EditMoneyRecord::route('/{record}/edit'),
        ];
    }
}
