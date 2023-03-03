<?php

namespace App\Filament\Resources;

use App\Enums\UserType;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('Customer First Name')->required()->columnSpan(12),

                TextInput::make('last_name')->label('Customer Surname')->required()->columnSpan(12),

                TextInput::make('address')->label('Customer Address')->required()->columnSpan(12),

                TextInput::make('phone_number')->label('Customer Phone')->required()->unique(ignoreRecord: true)->columnSpan(12),

                Select::make('role')
                    ->options(UserType::getOptionsForFilament())->columnSpan(12),

                Select::make('referred_by')->label('Sponsor Phone ')->searchable()->required()->columnSpan(12)
                    ->getSearchResultsUsing(fn (string $search) => User::query()->where(function ($query) use ($search) {
                        $query
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    })->limit(20)->pluck('phone_number', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Closure $set) => $set('title', User::query()->find($state)?->name))
                    ->hidden(fn (string $context) => $context == 'edit'),

                TextInput::make('title')
                    ->label('Sponsor Name')->columnSpan(12)
                    ->disabled()
                    ->hidden(fn (string $context) => $context == 'edit'),

                Toggle::make('active'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('first_name')->searchable(),
                TextColumn::make('last_name')->searchable(),
                TextColumn::make('phone_number')->searchable(),
                BadgeColumn::make('role')
                    ->enum(UserType::getOptionsForFilament())->colors([
                        'primary',
                        'secondary' => static fn ($state): bool => $state === UserType::RESELLER->value,
                    ]),
                TextColumn::make('address')->searchable(),
                TextColumn::make('referral.name')->label('Sponsor')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
