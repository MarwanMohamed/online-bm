<?php

namespace App\Filament\Actions;

use Filament\Actions\Concerns\CanImportRecords;
use Filament\Actions\ImportAction as BaseImportAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\Csv\Bom;
use League\Csv\Reader as CsvReader;
use League\Csv\Writer;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RenewalImportAction extends BaseImportAction
{
    /**
     * Accept both CSV and XLSX (and XLS) file types.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->form(function ($action): array {
            $csvAcceptedTypes = [
                'text/csv',
                'text/x-csv',
                'application/csv',
                'application/x-csv',
                'text/comma-separated-values',
                'text/x-comma-separated-values',
                'text/plain',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
            ];

            return array_merge([
                FileUpload::make('file')
                    ->label(__('filament-actions::import.modal.form.file.label'))
                    ->placeholder(__('filament-actions::import.modal.form.file.placeholder'))
                    ->acceptedFileTypes($csvAcceptedTypes)
                    ->rules($action->getFileValidationRules())
                    ->afterStateUpdated(function (FileUpload $component, Component $livewire, \Filament\Forms\Set $set, ?TemporaryUploadedFile $state) use ($action) {
                        if (! $state instanceof TemporaryUploadedFile) {
                            return;
                        }

                        try {
                            $livewire->validateOnly($component->getStatePath());
                        } catch (ValidationException $exception) {
                            $component->state([]);
                            throw $exception;
                        }

                        $csvStream = $this->getUploadedFileStream($state);
                        if (! $csvStream) {
                            return;
                        }

                        $csvReader = CsvReader::createFromStream($csvStream);
                        if (filled($csvDelimiter = $this->getCsvDelimiter($csvReader))) {
                            $csvReader->setDelimiter($csvDelimiter);
                        }
                        $csvReader->setHeaderOffset($action->getHeaderOffset() ?? 0);
                        $csvColumns = $csvReader->getHeader();

                        $lowercaseCsvColumnValues = array_map(Str::lower(...), $csvColumns);
                        $lowercaseCsvColumnKeys = array_combine($lowercaseCsvColumnValues, $csvColumns);

                        $set('columnMap', array_reduce($action->getImporter()::getColumns(), function (array $carry, \Filament\Actions\Imports\ImportColumn $column) use ($lowercaseCsvColumnKeys, $lowercaseCsvColumnValues) {
                            $carry[$column->getName()] = $lowercaseCsvColumnKeys[
                                Arr::first(array_intersect($lowercaseCsvColumnValues, $column->getGuesses()))
                            ] ?? null;
                            return $carry;
                        }, []));
                    })
                    ->storeFiles(false)
                    ->visibility('private')
                    ->required()
                    ->hiddenLabel(),
                Fieldset::make(__('filament-actions::import.modal.form.columns.label'))
                    ->columns(1)
                    ->inlineLabel()
                    ->schema(function (\Filament\Forms\Get $get) use ($action): array {
                        $csvFile = Arr::first((array) ($get('file') ?? []));
                        if (! $csvFile instanceof TemporaryUploadedFile) {
                            return [];
                        }
                        $csvStream = $this->getUploadedFileStream($csvFile);
                        if (! $csvStream) {
                            return [];
                        }
                        $csvReader = CsvReader::createFromStream($csvStream);
                        if (filled($csvDelimiter = $this->getCsvDelimiter($csvReader))) {
                            $csvReader->setDelimiter($csvDelimiter);
                        }
                        $csvReader->setHeaderOffset($action->getHeaderOffset() ?? 0);
                        $csvColumns = $csvReader->getHeader();
                        $csvColumnOptions = array_combine($csvColumns, $csvColumns);
                        return array_map(
                            fn (\Filament\Actions\Imports\ImportColumn $column): Select => $column->getSelect()->options($csvColumnOptions),
                            $action->getImporter()::getColumns(),
                        );
                    })
                    ->statePath('columnMap')
                    ->visible(fn (\Filament\Forms\Get $get): bool => Arr::first((array) ($get('file') ?? [])) instanceof TemporaryUploadedFile),
            ], $action->getImporter()::getOptionsFormComponents());
        });

    }

    /**
     * Allow CSV, TXT, XLSX, and XLS extensions (default trait only allows csv,txt).
     *
     * @return array<mixed>
     */
    public function getFileValidationRules(): array
    {
        $rules = parent::getFileValidationRules();
        $rules = array_map(function ($rule) {
            if (is_string($rule) && str_starts_with($rule, 'extensions:')) {
                return 'extensions:csv,txt,xlsx,xls';
            }
            return $rule;
        }, $rules);
        return $rules;
    }

    /**
     * Convert XLSX/XLS to CSV stream so the rest of the import pipeline works unchanged.
     *
     * @return resource|false
     */
    public function getUploadedFileStream(TemporaryUploadedFile $file)
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension() ?: pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extension, ['xlsx', 'xls'], true)) {
            return $this->xlsxToCsvStream($path);
        }

        return parent::getUploadedFileStream($file);
    }

    /**
     * Read XLSX/XLS and return a stream containing CSV data.
     *
     * @return resource|false
     */
    protected function xlsxToCsvStream(string $path)
    {
        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $stream = fopen('php://temp', 'r+');
            $writer = Writer::createFromStream($stream);
            $writer->setOutputBOM(Bom::Utf8);

            foreach ($rows as $row) {
                $writer->insertOne(array_map(fn ($cell) => (string) $cell, $row));
            }

            rewind($stream);
            return $stream;
        } catch (\Throwable) {
            return false;
        }
    }
}
