<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class PostcodeController extends Controller
{
    public function lookup(string $code): JsonResponse
    {
        $jsonPath = public_path('data/postcodes.json');

        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'Data not found'], 500);
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data['states'] as $state) {
            foreach ($state['cities'] as $city) {
                if (in_array($code, $city['postcodes'])) {
                    return response()->json([
                        'success' => true,
                        'postcode' => $code,
                        'city' => $city['name'],
                        'state' => $this->mapStateName($state['name']),
                        'state_code' => $state['code']
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Poskod tidak dijumpai'
        ], 404);
    }

    private function mapStateName(string $stateName): string
    {
        $mapping = [
            'Wp Kuala Lumpur' => 'Wilayah Persekutuan Kuala Lumpur',
            'Wp Labuan' => 'Wilayah Persekutuan Labuan',
            'Wp Putrajaya' => 'Wilayah Persekutuan Putrajaya',
        ];

        return $mapping[$stateName] ?? $stateName;
    }
}
