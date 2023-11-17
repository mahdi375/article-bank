<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['required', 'sometimes', 'string',  'min:3', 'max:254'],
            'categories' => ['required', 'sometimes', 'string',  'min:3', 'max:254'],
            'authors' => ['required', 'sometimes', 'string',  'min:3', 'max:254'],
            'sources' => ['required', 'sometimes', 'string',  'min:3', 'max:254'],
            'published_at' => ['required', 'sometimes', 'date'],
        ];
    }
}
