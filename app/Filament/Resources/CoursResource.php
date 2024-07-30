<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cours;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Semestre;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CoursResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CoursResource\RelationManagers;

class CoursResource extends Resource
{
    protected static ?string $model = Cours::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 70;
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
                Section::make("")
                ->icon("heroicon-o-book-open")
                ->description("Ajout Cours")
                ->schema([

                    Forms\Components\TextInput::make('classe_semestre')
                        ->label("Classe Sélectionnée")
                        ->placeholder(function(){
                            $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();
                            $Semestre=Semestre::where("id",session("semestre_id")[0] ?? 1)->first();
                            return $Classe->lib." | ".$Semestre->lib;
                        })
                        ->disabled()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('lib')
                        ->label("Cours")
                        ->required()
                        ->placeholder("Ex: Algorithmique")
                        ->live()
                        ->afterStateUpdated(function(Set $set,$state){
                            $rep=Cours::where("classe_id",session("classe_id")[0] ?? 1)
                                      ->where("cours.lib",$state)
                                     ->exists();
                            if($rep){
                                $set("lib",null);
                                Notification::make()
                                ->Title("Le Cours saisi existe déjà pour cette classe")
                                ->warning()
                                ->send();
                            }
                        })->maxLength(255),
                    Forms\Components\TextInput::make('ponderation')
                        ->required()
                        ->placeholder("Ex: 4")
                        ->numeric(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lib')
                    ->label("Cours")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ponderation')
                    ->numeric()
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
            'index' => Pages\ListCours::route('/'),
            'create' => Pages\CreateCours::route('/create'),
            'edit' => Pages\EditCours::route('/{record}/edit'),
        ];
    }
}
