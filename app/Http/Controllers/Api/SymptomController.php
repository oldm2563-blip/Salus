<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SymptomRequest;
use App\Http\Resources\SymptomResourse;
use App\Models\Symptom;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    #[OA\Get(
        path: '/symptoms',
        summary: 'Get all symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]

    public function index()
    {
        $symptom = SymptomResourse::collection(auth()->user()->symptoms);
        return response()->json([
            'success' => true,
            'data' => $symptom,
            'message' => 'all symptoms has been retrieved'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SymptomRequest $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/symptoms',
        summary: 'Create a new symptom',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'severity', 'date_recorded'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'itches'),
                    new OA\Property(property: 'severity', type: 'string', enum:['mild', 'moderate', 'severe'], example: 'mild'),
                    new OA\Property(property: 'description', type: 'string', example: '.....'),
                    new OA\Property(property: 'date_recorded', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(
                    property: 'notes',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'I carried it for a week, it gets stronger'
                    )
                ),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function store(SymptomRequest $request)
    {
        $incomingFields = $request->validated();
        $incomingFields['user_id'] = auth()->id();
        $symptom = Symptom::create($incomingFields);
        return response()->json([
            'success' => true,
            'data' => $symptom,
            'message' => 'a symptom has been added'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/symptoms/{symptom}',
        summary: 'Get one symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
    public function show(Symptom $symptom)
    {
        if(auth()->id() !== $symptom->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this symptom"
            ], 403);
        }
        return response()->json([
            'success' => true,
            'data' => $symptom,
            'message' => 'a symptom has been retrieved'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/symptoms/{symptom}',
        summary: 'Update a symptom',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'itches'),
                    new OA\Property(property: 'severity', type: 'string', enum:['mild', 'moderate', 'severe'], example: 'mild'),
                    new OA\Property(property: 'description', type: 'string', example: '.....'),
                    new OA\Property(property: 'date_recorded', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(
                    property: 'notes',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'I carried it for a week, it gets stronger'
                    )
                ),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function update(SymptomRequest $request, Symptom $symptom)
    {
        if(auth()->id() !== $symptom->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this symptom"
            ], 403);
        }
        $incomingFields = $request->validated();
        $symptom->update($incomingFields);
        return response()->json([
            'success' => true,
            'data' => $symptom,
            'message' => 'a symptom has been Updated'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
     #[OA\Delete(
        path: '/symptoms/{symptom}',
        summary: 'Get one symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
    public function destroy(Symptom $symptom)
    {
        if(auth()->id() !== $symptom->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this symptom"
            ], 403);
        }

        $symptom->delete();
        return response()->json([
            "success" => true,
            "message" => "the symptom was deleted"
        ]);
    }
}
