<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Volunteer;
use App\Models\Contribution;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();  
  
        $events_count = Event::whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->with(['volunteers'])
        ->orderBy('created_at', 'desc')
        ->count();
        
        $events = Event::whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->with(['volunteers'])
        ->orderBy('created_at', 'desc')
        ->get();

        $volunteersMasaol = Volunteer::where('status', 'مسئول')->get();

        $volunteersMashroaaMasaol = Volunteer::where('status', 'مشروع مسئول')->get();

        $volunteersMasaolCount = $volunteersMasaol->count();

        $volunteersMashroaaMasaolCount = $volunteersMashroaaMasaol->count();

        $volunteersMasaolContributionCount = $volunteersMasaol->filter(function ($volunteer) use ($startOfMonth, $endOfMonth) {
            return $volunteer->events()->whereBetween('events.created_at', [$startOfMonth, $endOfMonth])->exists();
        })->count();
    
        $volunteersMashroaaMasaolContributionCount = $volunteersMashroaaMasaol->filter(function ($volunteer) use ($startOfMonth, $endOfMonth) {
            return $volunteer->events()->whereBetween('events.created_at', [$startOfMonth, $endOfMonth])->exists();
        })->count();

         $masaolContributionPercentage = $volunteersMasaolCount > 0 
         ? ($volunteersMasaolContributionCount / $volunteersMasaolCount) * 100 
         : 0;
 
        $mashroaaMasaolContributionPercentage = $volunteersMashroaaMasaolCount > 0 
            ? ($volunteersMashroaaMasaolContributionCount / $volunteersMashroaaMasaolCount) * 100 
            : 0;
    
        $masaolContributionSum = $volunteersMasaol->sum(function ($volunteer) {
            return $volunteer->getCappedMonthlyParticipationAttribute();
        });
    
        $mashroaaMasaolContributionSum = $volunteersMashroaaMasaol->sum(function ($volunteer) {
            return $volunteer->getCappedMonthlyParticipationAttribute();
        });
    
        $averagemasaolContribution = $volunteersMasaolCount > 0 ? round($masaolContributionSum / $volunteersMasaolCount, 2) : 0;
        
        $averagemashroaaMasaolContribution = $volunteersMashroaaMasaolCount > 0 ? round($mashroaaMasaolContributionSum / $volunteersMashroaaMasaolCount, 2) : 0;
    
        $volunteers = Volunteer::whereBetween('voldate', [$startOfMonth, $endOfMonth])->get();

        $statistics = [
            'events_count' => $events_count,
            'events' => $events,
            'averagemasaolContribution' => $averagemasaolContribution,
            'averagemashroaaMasaolContribution' => $averagemashroaaMasaolContribution,
            'mashroaaMasaolContribution' => $mashroaaMasaolContributionSum,
            'masaolContributionPercentage' => round($masaolContributionPercentage, 2),
            'mashroaaMasaolContributionPercentage' => round($mashroaaMasaolContributionPercentage, 2),
            'volunteersMasaol_count' => $volunteersMasaolCount,
            'volunteersMashroaaMasaol_count' => $volunteersMashroaaMasaolCount,
            'volunteersMasaolContribution_count' => $volunteersMasaolContributionCount,
            'volunteersMashroaaMasaolContribution_count' => $volunteersMashroaaMasaolContributionCount,
            'total_volunteers_count' => $events->pluck('volunteers')->flatten()->count(),
            'unique_volunteers_count' => $events->pluck('volunteers')->flatten()->unique('id')->count(),
            'new_volunteers_count' => $volunteers->count(),
        ];
        
        return view('index',compact('statistics'));
    }
}
