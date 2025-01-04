<?php

namespace App\Filament\Resources\MembershipResource\RelationManagers;

use App\Models\Membership;
use App\Models\MembershipPrice;
use App\Services\Membership\MembershipPriceService;
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
use Illuminate\Support\Str;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    protected static string $dateFormat = 'Y-m-d';

    protected static string $displayDateFormat = 'F j, Y';

    public function form(Form $form): Form
    {
        /** @var Membership $membership */
        $membership = $this->getOwnerRecord();

        return $form
            ->schema([
                TextInput::make('membership_name')
                    ->label('Membership Name')
                    ->disabled()
                    ->default($membership->name),
                TextInput::make('membership_code')
                    ->label('Membership Code')
                    ->disabled()
                    ->default($membership->code),
                Select::make('type')
                    ->options([
                        'standard' => 'Standard',
                        'promotion' => 'Promotion',
                    ])
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get) => $get('type') === 'standard' ? $set('effective_to', null) : null)
                    ->native(false)
                    ->required(),
                TextInput::make('promotion_name')
                    ->helperText('This is only required if the price is a promotion')
                    ->maxLength(255)
                    ->requiredIf('type', 'promotion'),
                TextInput::make('female')
                    ->label('Female Price')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                TextInput::make('male')
                    ->label('Male Price')
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
                    ->disabled(fn (Get $get) => $get('type') === 'standard')
                    ->live(onBlur: true)
                    ->native(false)
                    ->requiredIf('type', 'promotion'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('female')
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100)),
                TextColumn::make('male')
                    ->formatStateUsing(fn (string $state) => (int) $state === 0 ? 'N/A' : number_format((int) $state / 100)),
                TextColumn::make('effective_from')
                    ->label('Effective From')
                    ->dateTime(static::$displayDateFormat)
                    ->sortable(),
                TextColumn::make('effective_to')
                    ->label('Effective To')
                    ->dateTime(static::$displayDateFormat)
                    ->sortable(),
                TextColumn::make('active')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn (MembershipPrice $record) => $this->getPriceStatus($record)),
            ])
            ->defaultSort('effective_from', 'desc')
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

    private function getPriceStatus(MembershipPrice $record): string
    {
        return app(MembershipPriceService::class)->getPriceStatus($record)->label();
    }

    /**
     * @param array{
     *     female: int,
     *     male: int,
     *     type?: string,
     *     promotion_name?: string|null,
     *     effective_from?: string,
     *     effective_to?: string|null,
     * } $data
     * @return array{
     *     female: int,
     *     male: int,
     *     type?: string,
     *     promotion_name?: string|null,
     *     effective_from?: string,
     *     effective_to?: string|null,
     * }
     */
    private function mutateRecordData(array $data): array
    {
        $data['female'] /= 100;
        $data['male'] /= 100;

        return $data;
    }

    /**
     * @param array{
     *     type: string,
     *     promotion_name: string|null,
     *     female: int,
     *     male: int,
     *     effective_from: string,
     *     effective_to: string|null,
     * }  $data
     * @return array{
     *     membership_id: int,
     *     membership_name: string,
     *     membership_code: string,
     *     type: string,
     *     promotion_name: string|null,
     *     female: int,
     *     male: int,
     *     effective_from: string,
     *     effective_to: string|null,
     * }
     */
    private function mutateFormData(array $data): array
    {
        /** @var Membership $membership */
        $membership = $this->getOwnerRecord();

        $data['membership_id'] = $membership->id;
        $data['membership_name'] = $membership->name;
        $data['membership_code'] = $membership->code;
        $data['female'] *= 100;
        $data['male'] *= 100;

        return $data;
    }
}
