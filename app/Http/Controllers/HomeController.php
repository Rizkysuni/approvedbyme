<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sempro;
use App\Models\User;

use Illuminate\View\View;

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
    public function index(): View
    {
        $sempros = Sempro::all();
        return view('home', compact('sempros'));
    } 
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dosenHome(): View
    {
        $dosenId = auth()->user()->id;
        $sempros = Sempro::where('dospem1', $dosenId)
                    ->orWhere('dospem2', $dosenId)
                    ->orWhere('penguji1', $dosenId)
                    ->orWhere('penguji2', $dosenId)
                    ->orWhere('penguji3', $dosenId)
                    ->get();

        return view('dosenHome', compact('sempros'));
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function koorHome(): View
    {
        return view('koorHome');
    }
}
