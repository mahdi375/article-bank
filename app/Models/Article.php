<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Filterable;
    use HasFactory;
    use Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
        ];
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
