<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Jury;
use Filament\Tables;
use App\Models\Classe;
use App\Models\Section as SectionModel;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClasseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClasseResource\RelationManagers;

class ClasseResource extends Resource
{
    protected static ?string $model = Classe::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 60;
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
                //
                Section::make()
                    ->icon("heroicon-o-building-office-2")
                    ->description("Ajout Classes")
                    ->schema([
                        TextInput::make("jury")
                            ->label("Jury Sélectionné")
                            ->disabled()
                            ->placeholder(function(){
                                $Section=SectionModel::where("id",session("section_id")[0] ?? 1)->first();
                                $Jury=Jury::where("id",session("jury_id")[0] ?? 1)->first();
                                return $Section->lib." | ".$Jury->lib;
                            }),
                        TextInput::make("lib")
                            ->label("Classe")
                            ->required()
                            ->live()
                            ->afterStateUpdated(function(Get $get,Set $set){

                                if(filled($get("lib"))){
                                    $rep=Classe::Where("jury_id",session("jury_id")[0] ?? 1)
                                                ->Where("lib",$get("lib"))
                                                ->exists();
                                    if($rep){
                                        $set("lib",null);
                                        Notification::make()
                                            ->title("La classe saisie a déjà été ajoutée pour ce jury")
                                            ->warning()
                                            ->send();
                                    }
                                }
                            })
                            ->placeholder("Ex: L1 IT")
                            ->maxlength(50),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("id")
                    ->label("Identifiant")
                    ->numeric()
                    ->sortable(),
                TextColumn::make("lib")
                    ->label("Classe")
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasse::route('/create'),
            'edit' => Pages\EditClasse::route('/{record}/edit'),
        ];
    }
}
