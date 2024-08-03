<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'User(s)';
    protected static ?string $navigationGroup ="Paramètres";
    protected static ?int $navigationSort = 130;

    public static function getNavigationBadge():string
    {
        if(Auth()->user()->hasRole(["Admin"])){
            return static::getModel()::count();

        }else{

            return static::getModel()::where("id",Auth()->user()->id)->count();
        }
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make("Définition Utilisateur")
                ->Icon("heroicon-o-user-plus")
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->placeholder("Ex: User")
                        ->maxLength(255)
                        ->columnSpan(1),
                    TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord:true)
                        ->placeholder("Ex: user@example.com")
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    // DateTimePicker::make('email_verified_at'),
                    TextInput::make('password')
                        ->password()
                        ->placeholder("Ex : password")
                        ->dehydrateStateUsing(fn($state)=>Hash::make($state))
                        ->dehydrated(fn($state)=> filled($state))
                        ->required(fn(Page $livewire) =>($livewire instanceof CreateUser) )
                        ->maxLength(255)
                        ->columnSpan(1),
                    Select::make("roles")
                        ->label("Roles")
                        ->searchable()
                        ->preload()
                        ->multiple()
                        ->relationship("roles","name")
                        ->hidden(fn():bool => !Auth()->user()->hasRole(["Admin"])),
                    //
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label("Identifiant")
                    ->numeric()
                    ->hidden(fn():bool => !Auth()->user()->hasRole(["Admin"]))
                    ->sortable(),
                TextColumn::make('name')
                    ->label("Nom")
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label("Roles")
                    ->searchable()
                    ->hidden(fn():bool => !Auth()->user()->hasRole(["Admin"])),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->label("Actions")
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     if(!Auth()->user()->hasRole(["Admin"])){

    //         return parent::getEloquentQuery()->where("id",Auth()->user()->id);
    //     }else{
    //         return parent::getEloquentQuery();

    //     }
    // }
}
