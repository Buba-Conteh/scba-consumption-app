<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumptionResource\Pages;
use App\Filament\Resources\ConsumptionResource\RelationManagers;
use App\Filament\Resources\CountryResource\Pages\ManageConsumption;
use App\Models\Batch;
use App\Models\Consumption;
use App\Models\Personnel;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\View\Components\Modal;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ramsey\Uuid\Type\Integer;
use Filament\Forms\Components\Section;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ConsumptionResource extends Resource
{
    protected static ?string $model = Consumption::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Q')
            ->translateLabel()
            ->description('Calculate Consumption Rate')
            ->schema([
                Select::make('personnel_id')
                ->label('personnel')
                ->options(Personnel::all()->pluck('name', 'id'))
               ->searchable()->columnSpan(2),
                Select::make('batch_id')
                ->label('Batch')
                ->options(Batch::all()->pluck('name', 'id'))
               ->searchable()->columnSpan(2),
            TextInput::make('departure_pressure')->translateLabel()->numeric()->required()->columnSpan(2),
            TextInput::make('return_pressure')->numeric()->required()->columnSpan(2)->translateLabel(),
            TimePicker::make('departure_time')->required()->columnSpan(2)->translateLabel(),
           TimePicker::make('return_time')->required()->columnSpan(2)->translateLabel(),
           TextInput::make('cylinder_volume')->required()->translateLabel(),
            ])->columns(4)
                 
            ]);
    }
  

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('personnel.name'),
                TextColumn::make('departure_pressure'),
                TextColumn::make('return_pressure'),
                TextColumn::make('consumption')->getStateUsing(function ($record){
                    return  $record->departure_pressure - $record->return_pressure;
                })->numeric(),
                // TextColumn::make('departure_time'),
                // TextColumn::make('return_time'),
                TextColumn::make('time_spent')
                ->getStateUsing(function ($record) {  
                 $returnTime = Carbon::parse($record->return_time);  
                 $departureTime = Carbon::parse($record->departure_time);  
                 $difference = $departureTime->diffInMinutes($returnTime);
                
                 return round($difference, 2);
                })->numeric(),
                TextColumn::make('cylinder_volume'),
                TextColumn::make('consumption_rate')->label('Q (L/m)'),
                TextColumn::make('grade'),
                TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'failed' => 'danger',
                })
            ])
            ->filters([
                // Searc::make(),
                TrashedFilter::make(),
                SelectFilter::make('personnel')
                    ->multiple()
                    ->label('Country')
                    ->relationship('personnel.country', 'name'),
                SelectFilter::make('Batch')
                    ->relationship('batch', 'name'),
            ], layout: FiltersLayout::AboveContentCollapsible)
            
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => ManageConsumption::route('/'),
            // 'create' => Modal::CreateConsumptionroute('/create'),
            // 'create' => Pages\CreateConsumption::route('/create'),
            // 'edit' => Pages\EditConsumption::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

 
}
