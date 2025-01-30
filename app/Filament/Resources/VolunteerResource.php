<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Volunteer;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\VolunteerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\VolunteerResource\RelationManagers;
use App\Filament\Resources\VolunteerResource\Pages\EditVolunteer;
use App\Filament\Resources\VolunteerResource\Pages\ListVolunteers;
use App\Filament\Resources\VolunteerResource\Pages\CreateVolunteer;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'المتطوعين';

    protected static ?string $navigationLabel = 'المتطوعين';
    protected static ?string $pluralModelLabel  = 'المتطوعين';

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('البيانات الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('الاسم')
                            ->placeholder('ادخل اسم المتطوع')
                            ->required()
                            ->columnSpan(1),
                        
                        TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->placeholder('ادخل رقم المتطوع')
                            ->required()
                            ->columnSpan(1),
                        
                        Select::make('gender')
                            ->label('النوع')
                            ->placeholder('اختر النوع')
                            ->required()
                            ->options([
                                'male' => 'ذكر',
                                'female' => 'أنثى',
                            ]),
                        
                        DatePicker::make('birthdate')
                            ->label('تاريخ الميلاد')
                            ->displayFormat('d/m/Y')
                            ->required(),
                        
                        DatePicker::make('voldate')
                            ->label('تاريخ التطوع')
                            ->displayFormat('d/m/Y')
                            ->required(),
                    ])
                    ->columns(['sm' => 1, 'lg' => 2])
                    ->columnSpan(['lg' => 2]),
                
                Section::make('بيانات إضافية')
                    ->schema([
                        TextInput::make('national')
                            ->label('الرقم القومي')
                            ->placeholder('ادخل الرقم القومي')
                            ->hidden(fn () => !auth()->user()->hasRole('hr'))
                            ->columnSpan(1),
                        
                        Checkbox::make('camp_48')
                            ->label('كامب 48')
                            ->hidden(fn () => !auth()->user()->hasRole('hr')),
                        
                        Textarea::make('notes')
                            ->label('الملاحظات')
                            ->columnSpanFull(),
                        
                        Textarea::make('address')
                            ->label('العنوان')
                            ->columnSpanFull(),
                    ])
                    ->columns(['lg' => 2])
                    ->columnSpan(['lg' => 2]),
                
                Section::make('الوسائط')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('vol_reseat')
                            ->label('إيصال التبرع')
                            ->collection('vol_reseats')
                            ->multiple()
                            ->downloadable()
                            ->hidden(fn () => !auth()->user()->hasRole('hr')),
                        
                        SpatieMediaLibraryFileUpload::make('vol_pic')
                            ->label('الصور الشخصية')
                            ->collection('vol_pics')
                            ->multiple()
                            ->downloadable()
                            ->image(),
                        
                        SpatieMediaLibraryFileUpload::make('vol_national')
                            ->label('صور البطاقة')
                            ->collection('vol_nationals')
                            ->multiple()
                            ->downloadable()
                            ->hidden(fn () => !auth()->user()->hasRole('hr')),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(['sm' => 1, 'lg' => 4]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('voldate', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(),
                
                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(),
                
                    SelectColumn::make('status')
                    ->label('التصنيف')
                    ->options([
                        'مسئول' => 'مسئول',
                        'مشروع مسئول' => 'مشروع مسئول',
                        'داخل المتابعة' => 'داخل المتابعة',
                        'خارج المتابعة' => 'خارج المتابعة',
                        'أشبال' => 'شبل',
                    ])
                    ->hidden(fn () => !auth()->user()->hasRole('Head'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('age')
                    ->label('العمر')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('voldate')
                    ->label('تاريخ التطوع')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('events.date')
                    ->label('المشاركات الأخيرة')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('التصنيف')
                    ->multiple()
                    ->searchable()
                    ->options([
                        'مسئول' => 'مسئول',
                        'مشروع مسئول' => 'مشروع مسئول',
                        'داخل المتابعة' => 'داخل المتابعة',
                        'خارج المتابعة' => 'خارج المتابعة',
                        'أشبال' => 'شبل',
                    ]),
                
                SelectFilter::make('gender')
                    ->label('النوع')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ])
                    ->searchable(),
                
                Tables\Filters\Filter::make('voldate')
                    ->label('فترة التطوع')
                    ->form([
                        DatePicker::make('from')->label('من تاريخ'),
                        DatePicker::make('to')->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],
                                fn($q) => $q->whereDate('voldate', '>=', $data['from']))
                            ->when($data['to'],
                                fn($q) => $q->whereDate('voldate', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye')->label(''),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil')->label(''),
                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash')->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                    ExportBulkAction::make()->label('تصدير البيانات'),
                ])->label('العمليات الجماعية'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EventsRelationManager::class,
            RelationManagers\RatingvsRelationManager::class,
            RelationManagers\HistoryRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }


}
