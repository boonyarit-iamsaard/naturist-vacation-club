<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * The generated password for the new user.
     */
    protected string $generatedPassword;

    /**
     * Mutate the form data before creating the record.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->generatedPassword = $this->generatePassword();

        $data['password'] = $this->generatedPassword;

        return $data;
    }

    /**
     * Get the notification to be displayed after creating the record.
     *
     * @return Notification|null The notification instance.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User created')
            ->body('The user has been created successfully.');
    }

    /**
     * Generate a random password.
     *
     * @return string The generated password.
     */
    protected function generatePassword(): string
    {
        return Str::password(12, symbols: false);
    }
}
