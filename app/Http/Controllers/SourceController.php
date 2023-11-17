<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceResource;
use App\Models\Source;
use App\Traits\RespondApi;
use Illuminate\Http\JsonResponse;

class SourceController extends Controller
{
    use RespondApi;

    public function index(): JsonResponse
    {
        return $this->response(SourceResource::collection(Source::paginate()));
    }
}
