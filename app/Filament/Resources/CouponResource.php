<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cours;
use App\Models\Coupon;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\CouponResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CouponResource\RelationManagers;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?string $modelLabel ="Fiches & Imprimés";
    protected static ?string $NavigationLabel ="Fiches & Imprimés";
    protected static ?int $navigationSort = 90;
    public static function getNavigationBadge():string
    {
        if(Auth()->user()->hasRole("Etudiant")){
            $Etudiant=Etudiant::where("user_id",Auth()->user()->id)->first();
            if($Etudiant){

                return static::getModel()::where("etudiant_id",Auth()->user()->id)
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
                ->icon("heroicon-o-clipboard-document-list")
                ->description("Enregister une fiche")
                ->schema([
                        Forms\Components\Select::make('etudiant_id')
                            ->label("Etudiant")
                            ->options(Etudiant::where("classe_id",session("classe_id")[0] ??1)->pluck("nom","id"))
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
                            }),
                        Repeater::make("elementscoupon")
                            ->label("Eléments Fiche")
                            ->relationship()
                            ->schema([
                                Select::make("cours_id")
                                     ->label("Cours")
                                     ->required()
                                     ->options(Cours::where("classe_id",session("classe_id")[0] ?? 1)->pluck("lib","id"))
                                     ->preload()
                                     ->distinct()
                                     ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                     ->searchable(),
                                TextInput::make("tj")
                                     ->label("Cote TJ")
                                     ->required()
                                     ->placeholder("Ex: 7")
                                     ->numeric()
                                     ->MinValue(1)
                                     ->MaxValue(10)
                                     ->live()
                                     ->afterStateUpdated(function($state,$set){
                                        if($state > 10){
                                            $set("tj",null);
                                            Notification::make()
                                            ->title("La cote ne peut être au-delà de 10")
                                            ->warning()
                                            ->send();
                                        }
                                     }),
                                TextInput::make("examenS1")
                                     ->label("Cote Examen Session 1")
                                     ->placeholder("Ex: 8")
                                     ->required()
                                     ->MinValue(1)
                                     ->MaxValue(10)
                                     ->numeric()
                                     ->live()
                                     ->afterStateUpdated(function($state,$set){
                                        if($state > 10){
                                            $set("tj",null);
                                            Notification::make()
                                            ->title("La cote ne peut être au-delà de 10")
                                            ->warning()
                                            ->send();
                                        }
                                     }),
                                TextInput::make("examenS2")
                                     ->label("Cote Examen Session 2")
                                     ->placeholder("Ex: 5")
                                     ->MinValue(1)
                                     ->MaxValue(10)
                                     ->numeric()
                                     ->afterStateUpdated(function($state,$set){
                                        if($state > 10){
                                            $set("tj",null);
                                            Notification::make()
                                            ->title("La cote ne peut être au-delà de 10")
                                            ->warning()
                                            ->send();
                                        }
                                     }),
                            ])->columnSpanFull()->columns(4)
                            ->addActionLabel('Ajouter un cours')
                            ->deleteAction( fn (Action $action) => $action->requiresConfirmation(),),
                ])->columns(2),

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
                Tables\Columns\TextColumn::make('etudiant.prenom')
                    ->label("Prénom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.genre')
                    ->label("Genre")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Enregistré le ")
                    ->dateTime("d/m/Y à H:i:s")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label("Modifié le ")
                    ->dateTime("d/m/Y à H:i:s")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort("coupons.created_at","desc")
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make("Imprimer Relevé")
                                   ->icon("heroicon-o-document-text")
                                   ->action(function(Coupon $clef){
                                        if(session("classe_id")){
                                            $classe_id=(int)session("classe_id")[0] ?? 1;
                                            return redirect()->route("coupon",compact("clef","classe_id"));
                                        }
                                        $classe_id=1;
                                        return redirect()->route("coupon",compact("clef","classe_id"));
                                   })
                                   ->hidden(function(Coupon $coupon){
                                        //récupération nombre de cours par classe
                                        $NbreCcl=Cours::where("classe_id",session("classe_id")[0] ?? 1)
                                                        ->where("semestre_id",session("semestre_id")[0] ?? 1)
                                                        ->count();

                                        //Récupération nombre de cours déjà renseigné sur le coupon
                                        $NbreCpon=Coupon::join("elementcoupons","elementcoupons.coupon_id","coupons.id")
                                                        ->where("classe_id",session("classe_id")[0] ?? 1)
                                                        ->where("semestre_id",session("semestre_id")[0] ?? 1)
                                                        ->where("coupons.id",$coupon->id)
                                                        ->count();
                                       if($NbreCcl==$NbreCpon){
                                            return false;
                                       }
                                        return true;


                                   }),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
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
            }else{

                return parent::getEloquentQuery()->where("etudiant_id",null);
            }

        }else{
            return parent::getEloquentQuery();

        }
    }
}
