<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RestaurantService;

class RestaurantController extends Controller
{
    public function __construct(
        private RestaurantService $restaurantService
    ){}

    public function index(Request $request){
        $search = $request->input('search') ?: 'Bang Sue';
        $searchCache = $this->restaurantService->getSearchCache($search);

        if(!$searchCache){

            $response = $this->restaurantService->fetchLocation($search);
            $location = $this->restaurantService->extractLocation($response);

            if ($response->status() != 200 || !$location) {
                return response()->json(['success' => false, 'message' => 'Location not found'], 404);
            }

            $data = [
                'keyword' => $search,
                'place_rank' => $location['place_rank'],
                'lat' => $location['lat'],
                'lon' => $location['lon'],
            ];

            $searchCache = $this->restaurantService->firstOrCreateSearchCache($data , $search);

        }

        $radius = 3000; //3 km
        $range = $this->restaurantService->calculateRange($radius); 
        $restaurants = $this->restaurantService->getRestaurants($range, $searchCache->lat, $searchCache->lon);
        if($restaurants->count() == 0){

            $response = $this->restaurantService->fetchRestaurants($radius, $searchCache->lat, $searchCache->lon);
            if ($response->status() != 200) {
                return response()->json(['success' => false, 'message' => 'Fail to fetch restaurants'], 503);
            }

            $data_upserts = $this->restaurantService->extractRestaurants($response);

            $this->restaurantService->upsertRestaurants($data_upserts);

            $restaurants = $this->restaurantService->getRestaurants($range, $searchCache->lat, $searchCache->lon);
        }

        return response()->json([
            'success' => true,
            'data' => $restaurants
        ]);

    }
}
