<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

class ParkingController extends Controller
{


    /**
 * @OA\Post(
 *     path="/api/parkings",
 *     summary="Crear un nuevo parking",
 *     tags={"Parkings"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","address","latitude","longitude"},
 *             @OA\Property(property="name", type="string", example="Parking Centro"),
 *             @OA\Property(property="address", type="string", example="Av. Corrientes 123"),
 *             @OA\Property(property="latitude", type="number", format="float", example=-34.6037),
 *             @OA\Property(property="longitude", type="number", format="float", example=-58.3816)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Parking creado"),
 *     @OA\Response(response=422, description="Error de validación")
 * )
 */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $parking = Parking::create($validated);

        return response()->json($parking, 201);
    }


/**
 * @OA\Get(
 *     path="/api/parkings/{id}",
 *     summary="Obtener un parking por ID",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del parking a consultar",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Parking Centro"),
 *             @OA\Property(property="address", type="string", example="Av. Corrientes 123"),
 *             @OA\Property(property="latitude", type="number", format="float", example=-34.6037),
 *             @OA\Property(property="longitude", type="number", format="float", example=-58.3816)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Parking no encontrado"
 *     )
 * )
 */ 
    public function show($id)
    {
        $parking = Parking::find($id);

        if (!$parking) {
            return response()->json(['error' => 'Parking not found'], 404);
        }

        return response()->json($parking);
    }


    /**
 * @OA\Get(
 *     path="/api/parkings/closest",
 *     summary="Obtener el parking más cercano a una ubicación",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="lat",
 *         in="query",
 *         required=true,
 *         description="Latitud del punto de búsqueda",
 *         @OA\Schema(type="number", format="float", example=-34.6037)
 *     ),
 *     @OA\Parameter(
 *         name="lng",
 *         in="query",
 *         required=true,
 *         description="Longitud del punto de búsqueda",
 *         @OA\Schema(type="number", format="float", example=-58.3816)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking más cercano",
 *         @OA\JsonContent(
 *             @OA\Property(property="distance_m", type="number", format="float", example=320.50),
 *             @OA\Property(
 *                 property="parking",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Parking Centro"),
 *                 @OA\Property(property="address", type="string", example="Av. Corrientes 123"),
 *                 @OA\Property(property="latitude", type="number", format="float", example=-34.6037),
 *                 @OA\Property(property="longitude", type="number", format="float", example=-58.3816)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron parkings"
 *     )
 * )
 */
    public function closest(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $validated['lat'];
        $lng = $validated['lng'];

        $closest = Parking::selectRaw('*, (
            6371000 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude))
            )
        ) AS distance', [$lat, $lng, $lat])
        ->orderBy('distance')
        ->first();

        if (!$closest) {
            return response()->json(['error' => 'No parkings found'], 404);
        }

        $distance = round($closest->distance, 2);

        if ($distance > 500) {
            Log::info('Solicitud lejana registrada', [
                'latitude' => $lat,
                'longitude' => $lng,
                'distance_m' => $distance,
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'parking' => $closest,
            'distance_m' => $distance,
        ]);
    }
}
