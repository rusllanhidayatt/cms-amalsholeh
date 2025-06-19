<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Settings extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }

    public function getTitle(): string
    {
        return 'Settings';
    }

    protected function getRedirectUrl(): string
    {
        return static::getUrl(['record' => Auth::id()]);
    }

    public function getRecord(): \App\Models\User
    {
        return Auth::user();
    }
}
