<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Ratingv;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Mokhosh\FilamentRating\Components\Rating;
use App\Filament\Resources\ReatingvResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RatingvResource\Pages\EditRatingv;
use App\Filament\Resources\ReatingvResource\RelationManagers;
use App\Filament\Resources\RatingvResource\Pages\ListRatingvs;
use App\Filament\Resources\RatingvResource\Pages\CreateRatingv;

class RatingvResource extends Resource
{
    protected static ?string $model = Ratingv::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'المتطوعين';

    protected static ?string $navigationLabel = 'تقيمات المتطوعين';
    protected static ?string $pluralModelLabel  = 'تقيمات المتطوعين';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        Select::make('volunteer_id')
                            ->relationship('volunteer', 'name')
                            ->label('اسم المتطوع')
                            ->searchable(['name', 'phone', 'status'])
                            ->preload()
                            ->native(false)
                            ->placeholder('اختر المتطوع')
                            ->required()
                            ->columnSpan(1),
                        
                        DatePicker::make('date')
                            ->label('التاريخ')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                Section::make('التقييمات')
                    ->schema([
                        Rating::make('commitment')
                            ->label('الالتزام')
                            ->stars(10)
                            ->default(1)
                            ->required()
                            ->columnSpan(1),
                        
                        Rating::make('following')
                            ->label('التابعية')
                            ->stars(10)
                            ->default(1)
                            ->required()
                            ->columnSpan(1),
                        
                        Rating::make('mixing')
                            ->label('الاختلاط')
                            ->stars(10)
                            ->default(1)
                            ->required()
                            ->columnSpan(1),
                        
                        Rating::make('head_rating')
                            ->label('تقييم رئيس اللجنة')
                            ->stars(10)
                            ->default(1)
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                Section::make('الإعدادات')
                    ->schema([
                        Checkbox::make('famliyday')
                            ->label('اليوم العائلي')
                            ->columnSpan(1),
                        
                        Checkbox::make('warning')
                            ->label('إنذار')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                Section::make('التقرير التفصيلي')
                    ->schema([
                        RichEditor::make('notes')
                            ->label('المحتوى')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'bulletList', 'orderedList',
                                'link', 'blockquote',
                                'codeBlock', 'h2', 'h3',
                            ])
                            ->fileAttachmentsDisk('local')
                            ->fileAttachmentsDirectory('attachments')
                            ->columnSpanFull()
                            ->required(),
                    ])
            ])
            ->columns(2);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('volunteer.name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('volunteer.phone')
                    ->label('الهاتف')
                    ->prefix('0')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('volunteer.status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'شبل' => 'warning',
                        'داخل المتابعة' => 'primary',
                        'مشروع مسئول' => 'success',
                        'مسئول' => 'success',
                        'خارج المتابعة' => 'danger',
                    })
                    ->toggleable(),
                
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                IconColumn::make('warning')
                    ->label('إنذار')
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
               
                
                SelectFilter::make('volunteer.status')
                    ->label('حالة المتطوع')
                    ->options([
                        'شبل' => 'شبل',
                        'داخل المتابعة' => 'داخل المتابعة',
                        'مشروع مسئول' => 'مشروع مسئول',
                        'مسئول' => 'مسئول',
                        'خارج المتابعة' => 'خارج المتابعة',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->label('تصدير'),
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
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
            'index' =>  ListRatingvs::route('/'),
            'create' => CreateRatingv::route('/create'),
            'edit' =>   EditRatingv::route('/{record}/edit'),
        ];
    }
}
