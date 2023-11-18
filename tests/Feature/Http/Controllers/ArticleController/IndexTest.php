<?php

use App\Models\Article;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->articleAttributes = [
        'id',
        'title',
        'description',
        'content',
        'url',
        'published_at',
        'created_at',
    ];
});

it('can paginate articles', function () {
    Article::factory(20)->create();
    
    getJson(route('api.articles.index'))
        ->assertSuccessful()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('meta.total', 20)
            ->has('data', 15)
            ->has('data.0', fn (AssertableJson $articleJson) => $articleJson
                ->hasAll($this->articleAttributes)
            )
            ->etc()
        );
});

it('can filter articles by categories', function () {

})->todo();

it('can filter articles by sources', function () {

})->todo();

it('can filter articles by authors', function () {

})->todo();

it('can filter articles by publish date', function () {

})->todo();

it('can search articles', function () {

})->todo();