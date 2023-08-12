<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait FullTextSearch
{
    protected function fullTextWildcards(string $term): string
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            if (strlen($word) >= 1) {
                $words[$key] = ' +' . $word . '*';
            }
        }
        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    public function scopeFullTextSearch(Builder $query, array $columns, string $term): void
    {
        $columnsText = implode(' , ', $columns);

        $query->whereRaw("MATCH ({$columnsText}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
    }
}
