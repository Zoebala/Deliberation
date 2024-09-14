<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Jury;
use Filament\Tables;
use App\Models\Cours;
use App\Models\Classe;
use App\Models\Recours;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RecoursResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RecoursResource\RelationManagers;

class RecoursResource extends Resource
    {
    protected static ?string $model = Recours::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 100;
    public static function getNavigationBadge():string
    {
        if(Auth()->user()->hasRole("Etudiant")){


            //on vérifie si le user en cours à un lien avec un étudiant de la base
            if(Etudiant::where("user_id",Auth()->user()->id)->exists()){
                $Etudiant=Etudiant::where("user_id",Auth()->user()->id)->first();
                return Recours::where("etudiant_id",$Etudiant->id)
                                        ->where("semestre_id",session("semestre_id")[0] ?? 1)
                                        ->where("classe_id",session("classe_id")[0] ?? 1)
                                        ->count();
            }else{
              return 0;
            }

        }
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
                ->icon("heroicon-o-document-text")
                ->description("Ajout Recours")
                ->schema([
                    Forms\Components\TextInput::make('classe_semestre')
                    ->label("Classe & Jury Sélectionnés")
                    ->placeholder(function(){
                        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();
                        $Jury=Jury::where("id",session("jury_id")[0] ?? 1)->first();
                        $libJury= $Jury ? $Jury->lib :"Veuillez choisir le jury";
                        return $libJury." | ".$Classe->lib;
                    })
                    ->disabled()
                    ->columnSpanFull(),
                    Forms\Components\Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->options(function(){
                            //on vérifie si le user en cours à un lien avec un étudiant de la base
                            if(Etudiant::where("user_id",Auth()->user()->id)->exists()){

                                return Etudiant::where("user_id",Auth()->user()->id)->pluck("nom","id");
                            }else{

                                return Etudiant::where("classe_id",session("classe_id")[0] ??1)->pluck("nom","id");
                            }


                        })
                        ->preload()
                        ->searchable()
                        ->live()
                        ->required()
                        ->helperText(function($state){
                            if($state){
                                $Etudiant=Etudiant::where("id",$state)->first();
                                return $Etudiant->nom." ".$Etudiant->postnom." ".$Etudiant->prenom." | Genre : ".$Etudiant->genre;
                            }else{
                                return "";
                            }
                        })->columnSpan(1),
                    Forms\Components\Select::make('cours_id')
                        ->label("Cours")
                        ->options(function(){
                            $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();
                            return Cours::where("classe_id",$Classe->id)->pluck("lib","id");

                        })
                        ->preload()
                        ->searchable()
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('motif')
                        ->label("Motif")
                        ->placeholder("Ex: Erreur Matérielle")
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    FileUpload::make('contenu')
                        ->label("Pièces Jointes")
                        ->multiple()
                        ->required()
                         ->openable()
                        ->downloadable()
                        ->maxSize("2048")
                        ->disk("public")->directory("recours")
                        ->columnSpanFull(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('etudiant.nom')
                    ->label("Nom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.postnom')
                    ->label("Postnom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.genre')
                    ->label("Genre")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cours.lib')
                    ->label("Cours")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motif')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('contenu')
                    ->searchable(),

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
            'index' => Pages\ListRecours::route('/'),
            'create' => Pages\CreateRecours::route('/create'),
            'edit' => Pages\EditRecours::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(Auth()->user()->hasRole(["Etudiant"])){
            //Identification de l'étudiant lié à l'utisateur
            $Etudiant=Etudiant::where("user_id",Auth()->user()->id)
                              ->where("classe_id",session("classe_id")[0] ?? 1)->first();
            if($Etudiant){

                return parent::getEloquentQuery()->where("etudiant_id",$Etudiant->id);
            }

            return parent::getEloquentQuery()->where("etudiant_id",null);
        }else{
            return parent::getEloquentQuery();

        }
    }
}
