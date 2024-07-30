<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Jury;
use Filament\Tables;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use App\Models\Section as SectionModel;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\JuryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JuryResource\RelationManagers;

class JuryResource extends Resource
{
    protected static ?string $model = Jury::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup ="Déliberation Management";
    protected static ?int $navigationSort = 40;
    public static function getNavigationBadge():string
    {
        return static::getModel()::Where("annee_id",session("Annee_id")[0] ?? 1)->count();
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
                ->icon("heroicon-o-building-library")
                ->description("Ajouter un Jury")
                ->schema([
                    Select::make("section_id")
                    ->label("Section")
                    ->options(SectionModel::all()->pluck("lib","id"))
                    ->preload()
                    ->live()
                    ->required()
                    ->searchable(),
                    TextInput::make("lib")
                    ->label("Jury")
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Get $get, Set $set){
                        if(filled($get("section_id")) && filled($get("lib"))){
                            $rep=Jury::whereSection_id($get("section_id"))
                                    ->whereLib($get("lib"))
                                    ->Where("Annee_id",session("Annee_id")[0])
                                    ->exists();
                            if($rep){
                                $set("lib",null);
                                Notification::make()
                                ->Title("le jury saisi existe déjà pour cette section")
                                ->warning()
                                ->send();
                            }
                        }
                    })
                    ->placeholder("Ex: Jury A"),

                    //Afficheur du Repétiteur
                    Toggle::make("afficher")
                          ->live()
                          ->label(function($state){
                                if($state==false)
                                    return "Associer des classes";
                                else
                                    return "Ne pas associer des classes";
                          }),

                    //Répétiteur
                    Repeater::make("classes")
                    ->relationship()
                    ->label("Classe")
                    ->visible(fn(Get $get):bool => $get("afficher")==true)
                    ->schema([
                        TextInput::make("lib")
                        ->label("Classe")
                        ->required()
                        ->live()
                        ->placeholder("Ex: L1 IT")
                        ->maxlength(50),
                    ])->columnSpanFull()
                        ->addActionLabel('Ajouter une classe')
                        ->deleteAction(
                            fn (Action $action) => $action->requiresConfirmation(),
                        )
                        ->grid(2),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("section.lib")
                ->label("Section")
                ->searchable()
                ->sortable(),
                TextColumn::make("lib")
                ->label("Jury")
                ->searchable()
                ->sortable(),
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
            'index' => Pages\ListJuries::route('/'),
            'create' => Pages\CreateJury::route('/create'),
            'edit' => Pages\EditJury::route('/{record}/edit'),
        ];
    }
}
