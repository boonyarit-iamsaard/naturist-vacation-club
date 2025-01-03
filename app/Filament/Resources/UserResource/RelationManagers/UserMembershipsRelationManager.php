<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Membership;
use App\Models\User;
use App\Models\UserMembership;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserMembershipsRelationManager extends RelationManager
{
    protected static string $relationship = 'userMemberships';

    protected static string $dateFormat = 'Y-m-d';

    protected static string $displayDateFormat = 'F j, Y';

    /** @var EloquentCollection<int, Membership>|null */
    protected ?EloquentCollection $memberships = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Select::make('membership_id')
                            ->label('Membership')
                            ->options($this->getMembershipOptions())
                            ->disableOptionWhen(fn (string $value) => $this->getDisabledMembershipOption($value))
                            ->required(),
                        DatePicker::make('start_date')
                            ->displayFormat(self::$displayDateFormat)
                            ->format(self::$dateFormat)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $this->setEndDate($set, $state))
                            ->native(false)
                            ->required(),
                        DatePicker::make('end_date')
                            ->displayFormat(self::$displayDateFormat)
                            ->format(self::$dateFormat)
                            ->label('End Date')
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('membership_name')
                    ->label('Membership')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('membership_number')
                    ->label('No.')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::upper($state)),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn (UserMembership $record) => $this->getUserMembershipStatus($record)),
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format(self::$displayDateFormat)),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format(self::$displayDateFormat)),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $this->prepareUserMembershipData($data))
                    ->after(fn () => $this->getOwnerRecord()->update(['role' => 'member'])),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get memberships with prices, lazy loading them only when needed
     *
     * @return EloquentCollection<int, Membership>
     */
    protected function getMemberships(): EloquentCollection
    {
        if ($this->memberships === null) {
            $this->memberships = Membership::with('prices')->get();
        }

        return $this->memberships;
    }

    /**
     * @return Collection<int, string>
     */
    private function getMembershipOptions(): Collection
    {
        return $this->getMemberships()
            ->pluck('name', 'id')
            ->mapWithKeys(fn (string $item, int $key) => [$key => Str::title($item)]);
    }

    private function getDisabledMembershipOption(string $value): bool
    {
        /** @var User $user */
        $user = $this->getOwnerRecord();

        $membership = $this->getMemberships()->firstWhere('id', $value);
        $activePrice = $membership->prices()->active()->first();

        return ($activePrice->{$user->gender} ?? 0) <= 0;
    }

    private function getUserMembershipStatus(UserMembership $record): string
    {
        return Carbon::parse($record->end_date)->startOfDay()->isPast() ? 'Expired' : 'Active';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareUserMembershipData(array $data): array
    {
        $membership = $this->getMemberships()->firstWhere('id', $data['membership_id']);

        /** @var User $user */
        $user = $this->getOwnerRecord();

        $data['user_id'] = $user->id;
        $data['user_name'] = $user->name;
        $data['user_email'] = $user->email;
        $data['user_gender'] = $user->gender;
        $data['membership_name'] = $membership->name;
        $data['membership_price_at_joining'] = $membership->price[$user->gender] ?? 0;

        return $data;
    }

    private function setEndDate(Set $set, ?string $state): void
    {
        if ($state) {
            $set('end_date', Carbon::parse($state)->addYear()->subDay()->format(self::$dateFormat));
        }
    }
}
