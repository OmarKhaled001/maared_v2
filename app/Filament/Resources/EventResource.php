<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Volunteer;
use Filament\Tables\Table;
use App\Models\Contribution;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Guava\Calendar\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\Summarizers\Count;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EventResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class EventResource extends Resource
{
    protected static ?string $navigationGroup = 'الفعاليات';

    protected static ?string $model = Event::class;
    protected static ?string $navigationLabel = 'الاحداث';
    protected static ?string $pluralModelLabel  = 'الاحداث';

    protected static bool $hasTitleCaseModelLabel = false;


    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
                ->columns([
                    'sm' => 1,
                    'xl' => 2,
                ])->schema([
                    DatePicker::make('date')
                        ->label('تاريخ الحدث')
                        
                    ->required(),
    
                    Select::make('volunteer_id')
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('الاسم')
                                ->placeholder('ادخل اسم المتطوع')
                                ->required()
                                ,
                                TextInput::make('phone')
                                ->label('رقم الهاتف')
                                ->placeholder('ادخل رقم المتطوع')
                                ->required()
                                ,
                                Select::make('gender')
                                ->options([
                                    '1' => 'ذكر',
                                    '2' => 'انثي',
                                ])->label('النوع')
                                ->required()
                                ->placeholder('اختر النوع'),
                                DatePicker::make('birthdate')
                                ->displayFormat('d/m/y')
                                ->required()
                                ->label('تاريخ الميلاد'),
                                DatePicker::make('voldate')
                                ->displayFormat('d/m/y')
                                ->required()
                                ->label('تاريخ التطوع'),
                        ])
                        ->relationship('volunteers','name')
                        ->label('أسم المتطوع')
                        ->placeholder('اختر اسماءالمتطوعين')
                        ->searchable(['name', 'phone','status'])
                        ->multiple()
                    ->required(),
    
                    Select::make('type')
                        ->options([
                            'الاحداث الاوفلاين' => [
                                'اجتماع اوفلاين' => 'اجتماع اوفلاين',
                                'معرض ملابس'  => 'معرض ملابس',
                                'قافلة اطعام' => 'قافلة اطعام',
                                'قافلة سوق' => 'قافلة سوق',
                                'حفلة ابطال تحدي' => 'حفلة ابطال تحدي',
                                'كرنفال' => 'كرنفال',
                                'كامب تعليمي' => 'كامب تعليمي',
                                'كامب' => 'كامب',
                                'اعمار' => 'اعمار',
                                'اجتماع اوفلاين' => 'اجتماع اوفلاين',
                                'اداريات اوفلاين' => 'اداريات اوفلاين',
                            ],
                            'الاحداث الاونلاين' => [
                                'متابعة' => 'متابعة',
                                'ميديا' => 'ميديا',
                                'اتصالات' => 'اتصالات',
                                'اجتماع اونلاين' => 'اجتماع اونلاين',
                                'اداريات اونلاين' => 'اداريات اونلاين',
                            ],
                            'اخري'    => 'اخري',
                        ])->label('نوع المشاركة')
                        ->placeholder('اختر النوع')
                    ->required()->live(),
    
                    TextInput::make('tshirt')
                        ->label('تيشرت رسالة')
                        ->numeric()
                    ->minValue(1),
                ]),
                Section::make()
                ->schema([
                    Textarea::make('notes')
                    ->label('الملاحظات')
                    ,
                    SpatieMediaLibraryFileUpload::make('event_scren')
                    ->collection('event_screns')
                    ->multiple()
                    ->downloadable()
                    ->optimize('jpg')
                    ->resize(50)
                    ->label('صورة الحدث')
                    ->downloadable()
                    ,
            ]),

            Section::make('بيانات الاجتماع')
                ->schema([
                    TextInput::make('meeting_head')
                    ->label('مسؤول الاجتماع'),
                    TextInput::make('meeting_position')
                    ->label('دور مسؤول الاجتماع')
                    ,
                    TextInput::make('meeting_goals')
                    ->label('هدف الاجتماع')
                    ,
                    Select::make('category_id')
                    ->relationship('category','name')
                    ->label('أسم اللجنة')
                    ->preload()
                    ->placeholder('اختر اللجنة')
                    ->searchable(['name']),
                    ])
                ->description('مسؤول عنه هيد الاجتماع    ')
            ->collapsed(),
                
            Section::make('بيانات الحدث الاوف لاين')
            ->schema([
                Select::make('maared_type')
                ->options([
                    'رمزي'    => 'رمزي',
                    'مجاني'    => 'مجاني',
                    'مميز'    => 'مميز',
                ])->label('نوع المعرض')
                ->placeholder('اختر نوع المعرض')
                ,

                Select::make('place_id')
                ->createOptionForm([
                        TextInput::make('name')
                        ->label('اسم المكان')
                        ->placeholder('ادخل اسم المكان')
                        ->required()
                        ,
                        TextInput::make('administrator_name')
                        ->label('اسم الدليل')
                        ->placeholder('ادخل اسم الدليل')
                        ->required()
                        ,
                        TextInput::make('administrator_phone')
                        ->label('رقم الهاتف')
                        ->placeholder('ادخل رقم السائق')
                        ->required()
                        ,
                        Toggle::make('is_admin')
                        ->label('جمعية شرعية')
                        ->required()
                        ,
                        Textarea::make('notes')
                        ->label('الملاحظات')
                        ,                                
                ])
                ->relationship('place','name')
                ->label('أسم المكان')
                ->placeholder('اختر اسم المكان')
                ->searchable(['name', 'administrator_name'])
                ->preload(),
                Select::make('car_type')
                ->options([
                    'ميكروباص'    => 'ميكروباص',
                    'كوستر'    => 'كوستر',
                    'ربع نقل'    => 'ربع نقل',
                    'جامبو'    => 'جامبو',
                ])->label('نوع العربية')
                ->placeholder('اختر نوع العربية')
                ,

            Select::make('driver_id')
                ->createOptionForm([
                TextInput::make('name')
                        ->label('الاسم')
                        ->placeholder('ادخل اسم السائق')
                        ->required(),
                        TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->placeholder('ادخل رقم السائق')
                        ->required(),
                        TextInput::make('national')
                        ->label('رقم الرخصة')
                        ->placeholder('ادخل رقم الرخصة')
                        ->required(),
                        
                ])
                ->relationship('driver','name')
                ->label('أسم السائق')
                ->placeholder('اختر اسم السائق')
                ->searchable(['name', 'phone'])
                ->preload()
                ,
            
            TimePicker::make('arrived_at')
            ->label('وقت الحضور'),
            TimePicker::make('move_at')
            ->label('وقت التحرك'),
            TimePicker::make('back_at')
            ->label('وقت العودة'),

            
            TextInput::make('amount')
            ->label('اجمالي مبلغ الايصال')
            ->placeholder('ادخل المبلغ  ')
            ,
            TextInput::make('expenses')
            ->label('اجمالي المصاريف')
            ->placeholder('ادخل المبلغ المصروف')
            ,
            DatePicker::make('pay_date')
            ->label(' تاريخ توريد الايصال')
            ->placeholder('ادخل تاريخ توريد')
            ,
            
            SpatieMediaLibraryFileUpload::make('event_reseat')
                ->collection('event_reseats')
                ->multiple()
                ->downloadable()
                ->label('صورة الايصال')
                ->optimize('jpg')
                ->resize(50)
                ->downloadable()
                ->columnSpan([
                    'sm' => 1,
                    'md' => 3,
                    'xl' => 3,
                ]),
           
            ])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'xl' => 3,
            ])
            ->description('مسؤول عنها لجنة المعارض')
            ->collapsed()->hidden(fn () => !auth()->user()->hasRole('maared')),
            
                

        ]);
    
    
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                // تاريخ الحدث
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
    
                // نوع المشاركة
                TextColumn::make('type')
                    ->label('نوع المشاركة')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
    
                // عدد المتطوعين
                TextColumn::make('volunteers_count')
                    ->label('عدد المشاركين')
                    ->counts('volunteers')
                    ->sortable()
                    ->toggleable(),
    
                // معلومات المتطوعين
                TextColumn::make('volunteers.name')
                    ->label('اسماء المشاركين')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),
    
                TextColumn::make('volunteers.phone')
                    ->label('أرقام التواصل')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),
    
                TextColumn::make('volunteers.age')
                    ->label('الاعمار')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),
    
                // ملاحظات
                TextColumn::make('notes')
                    ->label('ملاحظات إضافية')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
    
                // صور الحدث
                SpatieMediaLibraryImageColumn::make('event_screen')
                    ->label('صور الحدث')
                    ->collection('event_screens')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلتر التاريخ
                Filter::make('date')
                    ->label('فلترة حسب التاريخ')
                    ->form([
                        DatePicker::make('start_date')->label('من تاريخ'),
                        DatePicker::make('end_date')->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['start_date'],
                                fn($q) => $q->whereDate('date', '>=', $data['start_date']))
                            ->when($data['end_date'],
                                fn($q) => $q->whereDate('date', '<=', $data['end_date']));
                    }),
    
     
                // فلتر الشهر الحالي
                Filter::make('current_month')
                    ->label('أحداث هذا الشهر')
                    ->default()
                    ->query(fn(Builder $query) => 
                        $query->whereMonth('date', now()->month)->whereYear('date', now()->year)),
    
                // فلتر نوع المشاركة
                SelectFilter::make('type')
                    ->label('نوع المشاركة')
                    ->multiple()
                    ->options([
                        'الاحداث الاوفلاين' => [
                            'اجتماع اوفلاين' => 'اجتماع اوفلاين',
                            'معرض ملابس'  => 'معرض ملابس',
                            'قافلة اطعام' => 'قافلة اطعام',
                            'قافلة سوق' => 'قافلة سوق',
                            'حفلة ابطال تحدي' => 'حفلة ابطال تحدي',
                            'كرنفال' => 'كرنفال',
                            'كامب تعليمي' => 'كامب تعليمي',
                            'كامب' => 'كامب',
                            'اعمار' => 'اعمار',
                            'اجتماع اوفلاين' => 'اجتماع اوفلاين',
                            'اداريات اوفلاين' => 'اداريات اوفلاين',
                        ],
                        'الاحداث الاونلاين' => [
                            'متابعة' => 'متابعة',
                            'ميديا' => 'ميديا',
                            'اتصالات' => 'اتصالات',
                            'اجتماع اونلاين' => 'اجتماع اونلاين',
                            'اداريات اونلاين' => 'اداريات اونلاين',
                        ],
                        'اخري'    => 'اخري',
                    ]),
    
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
