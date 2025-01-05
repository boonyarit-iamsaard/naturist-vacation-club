<?php

namespace App\Filament\Resources\RoomTypeResource\RelationManagers;

use App\Models\RoomType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomTypeRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        /** @var RoomType $roomType */
        $roomType = $this->getOwnerRecord();

        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('room_type_name')
                            ->label('Room Type')
                            ->disabled()
                            ->default($roomType->name),
                        TextInput::make('room_price_code')
                            ->label('Room Code')
                            ->disabled()
                            ->default($roomType->code),
                        TextInput::make('name')
                            ->label('Room No.')
                            ->autofocus(false)
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var RoomType $roomType */
        $roomType = $this->getOwnerRecord();

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Room No.'),
                TextColumn::make('room_type_name')
                    ->label('Room Type')
                    ->default($roomType->name)
                    ->disabled(),
                TextColumn::make('room_price_code')
                    ->label('Room Code')
                    ->default($roomType->code)
                    ->disabled(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateFormData($data)),
            ])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateFormData($data)),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @param array{
     *     name: string,
     * }  $data
     * @return array{
     *     name: string,
     *     room_type_id: int,
     *     room_type_name: string,
     *     room_price_code: string,
     * }
     */
    private function mutateFormData(array $data): array
    {
        /** @var RoomType $roomType */
        $roomType = $this->getOwnerRecord();

        $data['room_type_id'] = $roomType->id;
        $data['room_type_name'] = $roomType->name;
        $data['room_price_code'] = $roomType->code;

        return $data;
    }
}
