<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    protected $query;

    public function __construct()
    {
        // Initialize the query with the Apartment model
        $this->query = Apartment::query();
    }

    public function filter(array $filters): Collection|array // Update the return type
    {
        // If no filters are provided, return all apartments
        if (empty($filters)) {
            $results = $this->query->get(); // Execute the query to get all apartments
            return $results->isEmpty() 
                ? ['message' => 'No apartments found'] // Return message if no apartments exist
                : $results; // Return all apartments
        }

        // Apply filters and fetch results
        $filteredResults = $this->applyFilters($filters)->get();

        return $filteredResults->isEmpty() 
            ? ['message' => 'No matches found'] // Return message if no matches found
            : $filteredResults; // Otherwise return the filtered results
    }

    protected function applyFilters(array $filters): Builder
    {
        if (!empty($filters['title'])) {
            $this->query->where('title', 'LIKE', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['price_min'])) {
            $this->query->where('price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $this->query->where('price', '<=', $filters['price_max']);
        }

        if (!empty($filters['rooms'])) {
            $this->query->where('rooms', $filters['rooms']);
        }

        if (isset($filters['available'])) {
            $this->query->where('available', $filters['available'] === 'true');
        }

        if (!empty($filters['area'])) {
            $this->query->where('area', $filters['area']);
        }

        if (!empty($filters['building_age'])) {
            $this->query->where('building_age', $filters['building_age']);
        }

        return $this->query; // Return the query builder
    }
}
