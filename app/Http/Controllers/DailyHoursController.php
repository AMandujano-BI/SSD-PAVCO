<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DailyHoursController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('DailyHours/Index');
    }
}
