<?php

namespace App\Filament\Widgets;



use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Volunteer;
use Carbon\Carbon;
use Filament\Forms\Components\Select;

class VolunteerMonthlyChart extends ApexChartWidget
{
    protected static ?string $chartId = 'volunteerMonthlyChart';
    protected static ?string $heading = 'مشاركتك في الأحداث';


    protected static ?string $pollingInterval = null; // تعطيل التحديث التلقائي

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 12,
            'sm' => 12, // شاشات صغيرة
            'md' => 12, // متوسطة
            'lg' => 12, // كبيرة
            'xl' => 12, // كبيرة جداً
        ];
    }
    


    protected function getHeading(): string
    {
        $volunteer = Volunteer::where('phone', auth()->user()->phone)->first();
        
        $monthlyCount = $volunteer->monthly_count; // استدعاء الـ attribute
        
        return 'مشاركتك في الأحداث - إجمالي الشهر الحالي: ' . $monthlyCount;
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('period')
                ->label('الفترة الزمنية')
                ->options([
                    'week' => 'أسبوع',
                    'month' => 'شهر',
                    'year' => 'سنة',
                ])
                ->default('week')
                ->reactive(), // محاذاة لليمين
        ];
    }

    protected function getOptions(): array
    {
        $volunteer = Volunteer::where('phone', auth()->user()->phone)->first();
        $data = $volunteer->getTimePeriodCounts($this->filterFormData['period'] ?? 'week');

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'direction' => 'rtl',
            ],
            'series' => [
                [
                    'name' => 'عدد الأحداث',
                    'data' => $data['counts'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['labels'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'Cairo, sans-serif',
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
        ];
    }
}