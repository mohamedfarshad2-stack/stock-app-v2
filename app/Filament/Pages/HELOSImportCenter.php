<?php

namespace App\Filament\Pages;

use App\Exports\HELOSMoneyRecordTemplateExport;
use App\Models\BusinessUnit;
use App\Models\FinanceCategory;
use App\Models\MoneyRecord;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class HELOSImportCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-upload';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'Import Center';
    protected static ?int $navigationSort = 50;
    protected static ?string $slug = 'helos-import-center';
    protected static string $view = 'filament.pages.helos-import-center';

    public $file;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('file')
                ->label('Upload Excel File')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                ->required(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Download Sample Excel')
                ->icon('heroicon-o-download')
                ->action(function () {
                    return Excel::download(new HELOSMoneyRecordTemplateExport(), 'helos-money-record-template.xlsx');
                }),
        ];
    }

    public function import(): void
    {
        $data = $this->form->getState();

        if (empty($data['file'])) {
            Notification::make()->title('Please upload a file first.')->danger()->send();
            return;
        }

        $rows = Excel::toArray([], storage_path('app/public/' . $data['file']))[0] ?? [];

        if (count($rows) <= 1) {
            Notification::make()->title('Excel file has no data rows.')->danger()->send();
            return;
        }

        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;
            $businessUnit = BusinessUnit::where('name', trim((string) ($row[1] ?? '')))->first();
            $category = FinanceCategory::where('name', trim((string) ($row[2] ?? '')))->first();

            if (! $businessUnit) {
                $errors[] = "Row {$line}: Business Unit not found.";
                continue;
            }

            if (! $category) {
                $errors[] = "Row {$line}: Category not found.";
                continue;
            }

            $type = strtolower(trim((string) ($row[3] ?? '')));
            if (! in_array($type, ['income', 'expense'], true)) {
                $errors[] = "Row {$line}: Type must be income or expense.";
                continue;
            }

            MoneyRecord::create([
                'record_date' => $row[0] ?? now()->toDateString(),
                'business_unit_id' => $businessUnit->id,
                'finance_category_id' => $category->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'amount' => (float) ($row[7] ?? 0),
                'payment_method' => $row[5] ?? null,
                'reference_no' => $row[10] ?? null,
                'description' => $row[11] ?? null,
                'status' => 'approved',
            ]);

            $imported++;
        }

        $message = "Imported {$imported} rows.";
        if (! empty($errors)) {
            $message .= ' Errors: ' . implode(' ', array_slice($errors, 0, 5));
            Notification::make()->title($message)->warning()->send();
            return;
        }

        Notification::make()->title($message)->success()->send();
        $this->form->fill();
    }
}
