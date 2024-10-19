<?php

namespace App\Filament\Pages;

use App\Models\Lookup\Lookup;
use App\Models\Setting;
use DateTimeZone;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;

class GeneralSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'phosphor-gear';
    protected static string $view = 'filament.pages.general-settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        $timezones = DateTimeZone::listIdentifiers();

        return $form
            ->schema([
                Select::make('timezone')->searchable()
                    ->options(array_combine($timezones, $timezones))
                    ->label('Timezone'), TextInput::make('date_format'),
                TextInput::make('datetime_format'),
                Toggle::make('google_recaptcha_enabled')->reactive(),
                TextInput::make('google_recaptcha_sitekey')->hidden(fn($get) => !$get('google_recaptcha_enabled')),
                TextInput::make('google_recaptcha_secretkey')->hidden(fn($get) => !$get('google_recaptcha_enabled')),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            foreach ($data as $key => $value) {
                Setting::where('key', $key)->update(['value' => $value]);
            }

        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
