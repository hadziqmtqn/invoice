<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZohoConfigResource\Pages;
use App\Models\Organization;
use App\Models\ZohoConfig;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZohoConfigResource extends Resource
{
    protected static ?string $model = ZohoConfig::class;

    protected static ?string $slug = 'zoho-configs';

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?string $navigationGroup = 'Integrations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('organization_id')
                    ->label('Organization')
                    ->options(Organization::all()->pluck('name', 'id'))
                    ->preload()
                    ->live()
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('grant_type')
                    ->options([
                        'authorization_code' => 'Authorization Code',
                        'refresh_token' => 'Refresh Token',
                    ])
                    ->searchable()
                    ->required(),

                TextInput::make('code')
                    ->required(fn (Get $get) => $get('grant_type') === 'authorization_code'),

                TextInput::make('client_id')
                    ->label('Client ID')
                    ->required(),

                TextInput::make('client_secret')
                    ->required(),

                TextInput::make('redirect_url')
                    ->required()
                    ->url(),

                TextInput::make('refresh_token')
                    ->required(fn(Get $get) => $get('grant_type') === 'refresh_token')
                    ->visibleOn('edit'),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?ZohoConfig $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?ZohoConfig $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization.name'),

                TextColumn::make('grant_type'),

                TextColumn::make('code'),

                TextColumn::make('client_id'),

                TextColumn::make('client_secret'),

                TextColumn::make('redirect_url'),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZohoConfigs::route('/'),
            'create' => Pages\CreateZohoConfig::route('/create'),
            'edit' => Pages\EditZohoConfig::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
