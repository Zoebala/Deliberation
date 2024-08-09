<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Jury;
use Filament\Tables;
use App\Models\Section;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Membrejury;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MembrejuryResource\Pages;
use App\Filament\Resources\MembrejuryResource\RelationManagers;

class MembrejuryResource extends Resource
{
    protected static ?string $model = Membrejury::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 50;
    public static function getNavigationBadge():string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make("Identité")
                        ->schema([

                            Forms\Components\Select::make('jury_id')
                                ->label("Jury")
                                ->options(Jury::Where("section_id",session("section_id")[0])->pluck("lib","id"))
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('nom')
                                ->required()
                                ->placeholder("Ex: Dupon")
                                ->maxLength(50),
                            Forms\Components\TextInput::make('postnom')
                                ->required()
                                ->placeholder("Ex: Thomsom")
                                ->maxLength(50),
                            Forms\Components\TextInput::make('prenom')
                                ->required()
                                ->placeholder("Ex: John")
                                ->maxLength(25),
                        ]),
                    Step::make("Contact & Fonction")
                        ->schema([

                            Forms\Components\TextInput::make('tel')
                                ->tel()
                                ->placeholder("Ex: 089XXXXXXX")
                                ->required()
                                ->maxLength(10),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->placeholder("Ex: username@example.com")
                                ->maxLength(50),
                            Forms\Components\TextInput::make('fonction')
                                ->datalist([
                                    "Président"=>"Président",
                                    "Secrétaire"=>"Secrétaire",
                                    "Membre"=>"Membre",
                                ])
                                ->placeholder("Ex: Secrétaire")
                                ->required()
                                ->maxLength(50)
                                ->columnSpanfull(),
                        ]),
                ])->columns(2)->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
        
                Tables\Columns\TextColumn::make('fonction')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nom')
                    ->label("Nom Complet")
                    ->getStateUsing(fn($record)=> $record->nom." ".$record->postnom." ".$record->prenom)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tel')
                    ->sortable()
                    ->searchable()
                    ->placeholder("Pas de phone"),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->placeholder("Pas d'adresse mail"),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
                ])->button()->label("Actions"),
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
            'index' => Pages\ListMembrejuries::route('/'),
            'create' => Pages\CreateMembrejury::route('/create'),
            'edit' => Pages\EditMembrejury::route('/{record}/edit'),
        ];
    }
}
