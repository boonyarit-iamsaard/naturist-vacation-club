<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomTypeResource\Pages\CreateRoomType;
use App\Filament\Resources\RoomTypeResource\Pages\EditRoomType;
use App\Filament\Resources\RoomTypeResource\Pages\ListRoomTypes;
use App\Filament\Resources\RoomTypeResource\RelationManagers\RoomsRelationManager;
use App\Models\RoomType;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;

    protected static ?string $navigationIcon = 'tabler-bed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(4),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('rooms_count')
                    ->label('Rooms')
                    // FIXME: this is expensive query, need to optimize
                    ->getStateUsing(fn (RoomType $record) => $record->rooms()->count())
                    ->sortable(),
                TextColumn::make('weekday_price')
                    ->label('Weekday Price')
                    // FIXME: this is expensive query, need to optimize
                    ->getStateUsing(fn (RoomType $record) => $record->prices()->standard()->active()->first()->weekday)
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100))
                    ->sortable(),
                TextColumn::make('weekend_price')
                    ->label('Weekend Price')
                    // FIXME: this is expensive query, need to optimize
                    ->getStateUsing(fn (RoomType $record) => $record->prices()->standard()->active()->first()->weekend)
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoomTypes::route('/'),
            'create' => CreateRoomType::route('/create'),
            'edit' => EditRoomType::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<RoomType>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
