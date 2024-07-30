<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EtudiantResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtudiantResource\RelationManagers;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 80;
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
                Section::make()
                ->icon("heroicon-o-user-plus")
                ->description("Ajout Etudiant")
                ->schema([
                    Forms\Components\TextInput::make('Classe Choisie')
                        ->disabled()
                        ->placeholder(function(){
                            $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();
                            return $Classe->lib;
                        })->columnSpanFull(),
                    Forms\Components\TextInput::make('nom')
                        ->required()
                        ->placeholder("Ex: Nzuzi")
                        ->maxLength(50),
                    Forms\Components\TextInput::make('postnom')
                        ->required()
                        ->placeholder("Ex: Kwanzambu")
                        ->maxLength(50),
                    Forms\Components\TextInput::make('prenom')
                        ->required()
                        ->placeholder("Ex: joel")
                        ->maxLength(25),
                    Forms\Components\Select::make('genre')
                        ->required()
                        ->options([
                            "F"=>"F",
                            "M"=>"M",
                        ])
                        ->preload()
                        ->searchable(),
                ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('postnom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prenom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genre')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }
}
