<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Password as RulesPassword;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('change_passord')
                ->form([
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->rule(RulesPassword::default()),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->required()
                        ->same('password')
                        ->rule(RulesPassword::default())
                ])
                ->action(function(array $data) {
                    $this->record->update([
                        'password' => bcrypt($data['password'])
                    ]);
                    $this->notify('success', 'Senha atualizada com sucesso!');
                }),
        ];
    }
}
