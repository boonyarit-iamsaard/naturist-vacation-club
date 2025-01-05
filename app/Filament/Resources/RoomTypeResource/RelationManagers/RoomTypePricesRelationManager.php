<?php

namespace App\Filament\Resources\RoomTypeResource\RelationManagers;

use App\Enums\PriceType;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Services\RoomType\RoomPriceService;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomTypePricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    protected static string $dateFormat = 'Y-m-d';

    protected static string $displayDateFormat = 'F j, Y';

    public function form(Form $form): Form
    {
        /** @var RoomType $roomType */
        $roomType = $this->getOwnerRecord();

        return $form
            ->schema([
                TextInput::make('room_type_name')
                    ->label('Room Type Name')
                    ->disabled()
                    ->default($roomType->name),
                TextInput::make('room_type_code')
                    ->label('Room Type Code')
                    ->disabled()
                    ->default($roomType->code),
                Select::make('type')
                    ->options(PriceType::class)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $state === PriceType::Standard->value ? $set('effective_to', null) : null)
                    ->native(false)
                    ->required(),
                TextInput::make('promotion_name')
                    ->helperText('This is only required if the price is a promotion')
                    ->maxLength(255)
                    ->requiredIf('type', PriceType::Promotion->value),
                TextInput::make('weekday')
                    ->label('Weekday Price')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                TextInput::make('weekend')
                    ->label('Weekend Price')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                DatePicker::make('effective_from')
                    ->label('Effective From')
                    ->displayFormat(static::$displayDateFormat)
                    ->format(static::$dateFormat)
                    ->minDate(now()->addDay()->format(static::$dateFormat))
                    ->default(now()->addDay()->format(static::$dateFormat))
                    ->live(onBlur: true)
                    ->native(false)
                    ->required(),
                DatePicker::make('effective_to')
                    ->label('Effective To')
                    ->displayFormat(static::$displayDateFormat)
                    ->format(static::$dateFormat)
                    ->minDate(fn (Get $get) => Carbon::parse($get('effective_from'))->addDay()->format(static::$dateFormat))
                    ->disabled(fn (Get $get) => $get('type') === PriceType::Standard->value)
                    ->live(onBlur: true)
                    ->native(false)
                    ->requiredIf('type', PriceType::Promotion->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (PriceType $state) => $state->getLabel()),
                TextColumn::make('weekday')
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100)),
                TextColumn::make('weekend')
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100)),
                TextColumn::make('effective_from')
                    ->label('Effective From')
                    ->dateTime(static::$displayDateFormat)
                    ->sortable(),
                TextColumn::make('effective_to')
                    ->label('Effective To')
                    ->dateTime(static::$displayDateFormat)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn (RoomPrice $record) => $this->getPriceStatus($record)),
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
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateFormData($data))
                    ->mutateRecordDataUsing(fn (array $data) => $this->mutateRecordData($data)),
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
     *     type: string,
     *     promotion_name: string|null,
     *     weekday: int,
     *     weekend: int,
     *     effective_from: string,
     *     effective_to: string|null,
     * } $data
     * @return array{
     *     room_type_id: int,
     *     room_type_name: string,
     *     room_type_code: string,
     *     type: string,
     *     promotion_name: string|null,
     *     weekday: int,
     *     weekend: int,
     *     effective_from: string,
     *     effective_to: string|null,
     * }
     */
    private function mutateFormData(array $data): array
    {
        /** @var RoomType $roomType */
        $roomType = $this->getOwnerRecord();

        $data['weekday'] *= 100;
        $data['weekend'] *= 100;
        $data['room_type_id'] = $roomType->id;
        $data['room_type_name'] = $roomType->name;
        $data['room_type_code'] = $roomType->code;

        return $data;
    }

    /**
     * @param array{
     *     id: int,
     *     weekday: int,
     *     weekend: int,
     *     type: PriceType,
     *     promotion_name: string|null,
     *     effective_from: Carbon,
     *     effective_to: Carbon|null,
     *     room_type_id: int,
     *     room_type_name: string,
     *     room_type_code: string,
     *     created_at: Carbon,
     *     updated_at: Carbon,
     *     deleted_at: Carbon|null,
     * } $data
     * @return array{
     *     id: int,
     *     weekday: int,
     *     weekend: int,
     *     type: PriceType,
     *     promotion_name: string|null,
     *     effective_from: Carbon,
     *     effective_to: Carbon|null,
     *     room_type_id: int,
     *     room_type_name: string,
     *     room_type_code: string,
     *     created_at: Carbon,
     *     updated_at: Carbon,
     *     deleted_at: Carbon|null,
     * }
     */
    private function mutateRecordData(array $data): array
    {
        $data['weekday'] /= 100;
        $data['weekend'] /= 100;

        return $data;
    }

    private function getPriceStatus(RoomPrice $record): string
    {
        return app(RoomPriceService::class)->getPriceStatus($record)->getLabel();
    }
}
