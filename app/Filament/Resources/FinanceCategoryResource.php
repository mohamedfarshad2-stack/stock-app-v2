<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceCategoryResource\Pages;
use App\Models\FinanceCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class FinanceCategoryResource extends Resource
{
    protected static ?string $model = FinanceCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Finance Categories';

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
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('code'),
            Forms\Components\Select::make('type')
                ->options(self::TYPE_OPTIONS)
                ->default('expense')
                ->required(),
            Forms\Components\Select::make('parent_id')->label('Parent Category')->options(FinanceCategory::query()->pluck('name', 'id'))->searchable(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('code')->searchable(),
            Tables\Columns\BadgeColumn::make('type'),
            Tables\Columns\TextColumn::make('parent.name')->label('Parent'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->filters([
            Tables\Filters\SelectFilter::make('type')->options(self::TYPE_OPTIONS),
            Tables\Filters\TernaryFilter::make('is_active'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinanceCategories::route('/'),
            'create' => Pages\CreateFinanceCategory::route('/create'),
            'edit' => Pages\EditFinanceCategory::route('/{record}/edit'),
        ];
    }
}
