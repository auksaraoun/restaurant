<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SearchCache;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Response;

class RestaurantService
{
    public function getSearchCache(string $search): ?SearchCache {
        return SearchCache::whereKeyword($search)->first();
    }

    public function fetchLocation(string $search): Response{
        return Http::withHeaders([
            'User-Agent' => 'Restaurant/1.0'
        ])
        ->get("https://nominatim.openstreetmap.org/search?q={$search}&format=json&countrycodes=th");
    }

    public function extractLocation(Response $response): ?array{
        $responseBody = $response->collect();
        $location = $responseBody->where('place_rank', 12)->first();
        return $location;
    }

    public function firstOrCreateSearchCache(array $data, $keyword): SearchCache{
        return SearchCache::firstOrCreate(
            ['keyword' => $keyword],
            $data
        );
    }

    public function calculateRange(float $radius): float{
        return $radius / 111000;
    }

    public function getRestaurants(float $range, float $lat, float $lon): Collection{
        return Restaurant::whereBetween('lat', [$lat - $range, $lat + $range])
            ->whereBetween('lon', [$lon - $range, $lon + $range])
            ->get();
    }

    public function fetchRestaurants(float $radius, float $lat, float $lon): Response{
        return Http::withHeaders([
            'User-Agent' => 'Restaurant/1.0'
        ])
            ->withBody(
                '[out:json];node["amenity"="restaurant"](around:' . $radius . ',' . $lat . ',' . $lon . ');out 50;',
                'text/plain'
            )
            ->post("https://overpass-api.de/api/interpreter");
    }

    public function extractRestaurants(Response $response): array{
        $responseBody = $response->collect();
        $restaurants = [];

        if(!empty($responseBody)){
            foreach ($responseBody['elements'] as $element) {

                $name = $element['tags']['name'] ?? null;
                $name_th = $element['tags']['name_th'] ?? null;
                $name_en = $element['tags']['name_en'] ?? null;

                if(!$name && !$name_th && !$name_en) {
                    continue;
                }

                $restaurants[] = [
                    'osm_id' => $element['id'],
                    'name' => $name,
                    'name_th' => $name_th,
                    'name_en' => $name_en,
                    'cuisine' => $element['tags']['cuisine'] ?? null,
                    'lat' => $element['lat'],
                    'lon' => $element['lon'],
                ];

            }
        }
        return $restaurants;
    }

    public function upsertRestaurants(array $data_upserts): int{
        return Restaurant::upsert($data_upserts, ['osm_id']);
    }
}
