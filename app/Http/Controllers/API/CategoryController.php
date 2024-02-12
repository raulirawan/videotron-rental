<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //
    public function fetch()
    {
        $categories = Category::with(['videotron'])->get();
        return ResponseFormatter::success($categories, 'List Data Category');
    }
}
