<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleIndexRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\RespondApi;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    use RespondApi;

    public function index(ArticleIndexRequest $request): JsonResponse
    {
        $paginatedArticles = Article::filters($request->validated())
            ->paginate();

        return $this->response(ArticleResource::collection($paginatedArticles));
    }

    public function show(Article $article): JsonResponse
    {
        $article->load(['categories', 'authors', 'source']);

        return $this->response(ArticleResource::make($article));
    }
}
