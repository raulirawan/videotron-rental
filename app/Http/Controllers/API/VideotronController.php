<?php

namespace App\Http\Controllers\API;

use App\Models\Videotron;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class VideotronController extends Controller
{
    public function fetch()
    {
        $videotrons = Videotron::with(['category'])->get();
        return ResponseFormatter::success($videotrons, 'List Data Videotron');
    }
}
