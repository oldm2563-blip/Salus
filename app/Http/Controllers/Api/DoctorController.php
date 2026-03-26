<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class DoctorController extends Controller
{
    #[OA\Get(
        path: '/doctors',
        summary: 'Get all doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        responses: [
            new OA\Response(response: 200, description: 'List of doctors'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]
    public function index ()
    {
        $doctors = Doctor::all();
        return response()->json([
            'success' => true,
            'data' => $doctors,
            'message' => 'Doctors have been retrieved'
        ]);
    }
    #[OA\Get(
        path: '/doctors/{id}',
        summary: 'Get all doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'fetch doctor'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]
    public function show ($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $doctor,
            'message' => 'The doctor have been retrieved'
        ]);
    }
    #[OA\Get(
        path: '/doctors/search',
        summary: 'search for doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        parameters: [
                    new OA\Parameter(name: 'specialty', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: "General Medicine")),
                    new OA\Parameter(name: 'city', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: "rabat")),
                ],
        responses: [
            new OA\Response(response: 200, description: 'List of doctors'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]
    
    public function search (Request $request)
    {
        $query = Doctor::query();

        if($request->specialty){
            $query->where('specialty', 'like' , "%". $request->specialty . "%");
        }
        if($request->city){
            $query->where('city', "like" ,'%' . $request->city . "%");
        }

        $doctors = $query->latest()->get();

        return response()->json([
            'success' => true,
            "doctors" => $doctors,
            "message" => "these are the doctors that are found" 
        ]);
    }
}
