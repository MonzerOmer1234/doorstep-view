<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Search
{
    protected $query;

    public function __construct()
    {
        // Initialize the query with the Property model
        $this->query = Property::query();
    }

    public function filter(array $filters): Collection
    {
        // If no filters are provided, return all properties
        if (empty($filters)) {
            return $this->query->get(); // Return all properties directly
        }

        // Apply filters and fetch results
        return $this->applyFilters($filters)->get();
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
            $this->query->where('building_age', '<=', $filters['building_age']);
        }

        return $this->query; // Return the query builder
    }
}
