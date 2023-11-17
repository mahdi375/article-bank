<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    private array $sources = [
        'Guardian' => 'theguardian.com',
        'NewYorkTimes' => 'nytimes.com',
    ];

    public function run(): void
    {
        foreach ($this->sources as $name => $domain) {
            Source::firstOrCreate([
                'name' => $name,
            ], [
                'domain' => $domain,
            ]);
        }
    }
}
