<?php

namespace App\ArticleProviders;

interface ArticleProvider
{
    public function import(): void;
}
