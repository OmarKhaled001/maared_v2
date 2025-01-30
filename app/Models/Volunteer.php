<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Volunteer extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia ,LogsActivity;

    protected $guarded = [];

    protected $appends = ['age'];
    
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }

    public function events( ){ 

        return $this->belongsToMany(Event::class ,'event_volunteer')->withTimestamps(); 
    }

    public function histories( ){ 

        return $this->belongsToMany(History::class ,'volunteer_history')->withTimestamps(); 
    }

    public function categories( ){ 

        return $this->belongsToMany(Category::class ,'volunteer_category')->withTimestamps(); 
    }

    
    public function ratingvs(){

        return $this->hasMany(Ratingv::class ); 

        
    }
    
    public function contributions(){

        return $this->hasMany(Contribution::class ); 

        
    }
    

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getMonthlyCountAttribute()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        return $this->events()
        ->whereBetween('event_volunteer.created_at', [$startOfMonth, $endOfMonth])
        ->distinct('event_volunteer.created_at')
        ->count() ;
      

    }


    public function getCappedMonthlyParticipationAttribute()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
    
        $uniqueDailyParticipations = $this->events()
            ->whereBetween('event_volunteer.created_at', [$startOfMonth, $endOfMonth])
            ->distinct('event_volunteer.created_at')
            ->count();
    
        return min($uniqueDailyParticipations, 8);
    }


    public function getMonthlyCountsLastYear(): array
    {
        $counts = [];
        $labels = [];
    
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();
    
            $count = $this->events()
                ->wherePivotBetween('created_at', [$start, $end])
                ->count();
    
            $counts[] = $count;
            $labels[] = $date->format('M Y');
        }
    
        return [
            'counts' => $counts,
            'labels' => $labels,
        ];
    }

    // في الموديل Volunteer
public function getTimePeriodCounts(string $period): array
{
    $counts = [];
    $labels = [];
    $now = Carbon::now()->locale('ar');

    switch ($period) {
        case 'week':
            $days = 7;
            for ($i = 0; $i < $days; $i++) {
                $date = $now->copy()->subDays($i);
                $count = $this->events()
                    ->whereDate('event_volunteer.created_at', $date->format('Y-m-d'))
                    ->count();
                
                // إدراج البيانات من الأحدث إلى الأقدم
                array_unshift($counts, $count);
                array_unshift($labels, $date->translatedFormat('l')); // اسم اليوم بالعربية
            }
            break;

        case 'month':
            $weeks = 4;
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $start = $now->copy()->subWeeks($i)->startOfWeek();
                $end = $start->copy()->endOfWeek();
                $count = $this->events()
                    ->whereBetween('event_volunteer.created_at', [$start, $end])
                    ->count();
                $counts[] = $count;
                $labels[] = $start->translatedFormat('j M') . ' - ' . $end->translatedFormat('j M');
            }
            break;

        case 'year':
            $months = 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $count = $this->events()
                    ->whereMonth('event_volunteer.created_at', $date->month)
                    ->whereYear('event_volunteer.created_at', $date->year)
                    ->count();
                $counts[] = $count;
                $labels[] = $date->translatedFormat('M Y');
            }
            break;
    }

    return [
        'counts' => $counts,
        'labels' => $labels,
    ];
}
    
    protected $casts = [
        'voldate' => 'date',
    ];

}
