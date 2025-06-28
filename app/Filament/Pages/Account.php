<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class Account extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.account';
    protected static ?string $title = 'Account';

    public string $name;
    public string $email;
    public ?string $current_password = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->required(),

            TextInput::make('current_password')
                ->label('Current Password')
                ->password()
                ->nullable(),

            TextInput::make('password')
                ->label('New Password')
                ->password()
                ->required(fn ($get) => filled($get('current_password')))
                ->minLength(8)
                ->confirmed()
                ->nullable(),

            TextInput::make('password_confirmation')
                ->label('Confirm New Password')
                ->password()
                ->required(fn($get) => filled($get('password')))
                ->same('password')
                ->nullable(),
        ];
    }

    public function submit(): void
    {
        $user = auth()->user();

        $user->name = $this->name;
        $user->email = $this->email;

        // Jika current_password diisi, password dan password_confirmation harus diisi
        if ($this->current_password) {
            if (!$this->password || !$this->password_confirmation) {
                $this->addError('password', 'Password and confirmation are required when changing password.');
                $this->addError('password_confirmation', 'Password and confirmation are required when changing password.');
                return;
            }

            // Validasi current password harus benar
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Current password is incorrect.');
                return;
            }

            // Validasi password & confirmation sama
            if ($this->password !== $this->password_confirmation) {
                $this->addError('password_confirmation', 'Password confirmation does not match.');
                return;
            }

            $user->password = Hash::make($this->password);
        }

        $user->save();

        Notification::make()
            ->title('Account updated successfully.')
            ->success()
            ->send();
    }
}
