<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Traits\RespondApi;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    use RespondApi;

    public function index(): JsonResponse
    {
        return $this->response(AuthorResource::collection(Author::paginate()));
    }
}
