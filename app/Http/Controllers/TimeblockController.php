<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timeblock;

class TimeblockController extends Controller
{
    public function index()
    {
        $timeblock = Timeblock::find(5);
        return date('h:i:s A', strtotime($timeblock->timestart));
    }
}
