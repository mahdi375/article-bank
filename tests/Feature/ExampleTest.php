<?php

use function Pest\Laravel\getJson;

it('tests the application home page', function () {
    getJson('/')
        ->assertOk();
});
