<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Actualite;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ActualiteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActualiteResource\RelationManagers;

class ActualiteResource extends Resource
{
    protected static ?string $model = Actualite::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Actualités';
    protected static ?string $modelLabel = 'Actualités';
    public static function canAccess():bool
    {
        if(!Auth()->user()->hasRole(["Admin"])){
            return false;
        }else{

            return true;
        }


    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Group::make([
                    Section::make()
                    ->schema([
                        TextInput::make('objet')
                            ->required()
                            ->placeholder("Ex: Objet")
                            ->maxLength(255)->columnSpanFull(),
                       MarkdownEditor::make('description')
                            ->required()
                            ->maxLength(255)->columnSpanFull(),
                    ])->columnSpan(2),
                    Section::make()
                    ->description("Ajouter l'image associée à une actualité")
                    ->icon("heroicon-o-camera")
                    ->schema([
                        FileUpload::make('photo')
                            ->required()
                            ->openable()
                            ->disk("public")->directory('actualites')->columnSpanFull(),
                    ])->columnSpan(1),
                ])->columns(3)->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('objet')
                ->searchable(),
            Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->formatStateUsing(function($state){
                    return substr($state,0,50)."...";
                }),
            ImageColumn::make('photo')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label("Publié le")
                ->dateTime("d/m/Y à H:i:s")
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
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
                ])->label("Actions")
                ->button()
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
            'index' => Pages\ListActualites::route('/'),
            'create' => Pages\CreateActualite::route('/create'),
            'edit' => Pages\EditActualite::route('/{record}/edit'),
        ];
    }
}
