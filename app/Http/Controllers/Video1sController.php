<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Video1sController extends Controller
{
    public function index()
    {
        return view('video1s.index');
    }
}
