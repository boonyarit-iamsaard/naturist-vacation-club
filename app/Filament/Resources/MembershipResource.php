<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages\CreateMembership;
use App\Filament\Resources\MembershipResource\Pages\EditMembership;
use App\Filament\Resources\MembershipResource\Pages\ListMemberships;
use App\Filament\Resources\MembershipResource\RelationManagers\PricesRelationManager;
use App\Models\Membership;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
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
use Illuminate\Support\Str;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'tabler-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Membership Name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => self::setMembershipCode($set, $state))
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Membership Code')
                    ->disabled()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(4),
                TextInput::make('room_discount')
                    ->label('Room Discount (%)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->badge()
                    ->searchable(),
                TextColumn::make('room_discount')
                    ->label('Room Discount (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('female')
                    ->label('Female Price')
                    ->getStateUsing(fn (Membership $record) => $record->prices()->standard()->active()->first()->female)
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100))
                    ->sortable(),
                TextColumn::make('male')
                    ->label('Male Price')
                    ->getStateUsing(fn (Membership $record) => $record->prices()->standard()->active()->first()->male)
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
            ->defaultSort('room_discount', 'desc')
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
            PricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberships::route('/'),
            'create' => CreateMembership::route('/create'),
            'edit' => EditMembership::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<Membership>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function setMembershipCode(Set $set, ?string $state): void
    {
        if ($state) {
            $set('code', Str::upper(Str::substr($state, 0, 1)));
        }
    }
}
