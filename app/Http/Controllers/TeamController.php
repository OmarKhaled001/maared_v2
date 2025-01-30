<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\History;
use App\Models\Volunteer;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function MasaolTeam() {

        $volunteers = Volunteer::where('status','مسئول')->get();
    
        return view('vol.masaol',[
            'volunteers' => $volunteers,
      
        ]);

    }
    public function MmasaolTeam() {

        $volunteers = Volunteer::where('status','مشروع مسئول')->get();
    
        return view('vol.mmasaol',[
            'volunteers' => $volunteers,
      
        ]);

    }
    public function new() {


        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();
    

        $volunteers = Volunteer::whereBetween('voldate', [$startOfMonth, $endOfMonth])->get();
       
    
 
        return view('vol.new',[
            'volunteers' => $volunteers,
      
        ]);

    }
    public function birthdate(){

        $currentMonth = now()->month;

        $volunteers = Volunteer::whereMonth('birthdate',$currentMonth)->get();
       
        return view('vol.birthdate',[
            'volunteers' => $volunteers,
      
        ]);
    }
}
