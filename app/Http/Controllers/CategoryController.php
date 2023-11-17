<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\RespondApi;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use RespondApi;

    public function index(): JsonResponse
    {
        return $this->response(CategoryResource::collection(Category::all()));
    }
}
