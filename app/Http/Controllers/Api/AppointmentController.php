<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use League\Uri\Http;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/appointments',
        summary: 'Get all appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointments'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointments not found'),
        ]
    )]
    public function index()
    {
        $appointments = auth()->user()->appointments()->get();
        return response()->json([
            "success" => true,
            "data" => $appointments,
            "message" => "appointments has been retrieved"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     #[OA\Post(
        path: '/appointments',
        summary: 'Create a new appointment',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['appointment_date', 'doctor_id'],
                properties: [
                    new OA\Property(property: 'appointment_date', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(property: 'status', type: 'string', enum:['pending','confirmed','cancelled'], example: 'pending or confirmed' ),
                    new OA\Property(property: 'doctor_id', type: 'integer' , example: '1'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function store(Request $request)
    {
        $date = Carbon::today();
        $incomingFields = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'doctor_id' => 'required|exists:doctors,id' ,
            'status' => 'sometimes|in:pending,confirmed'
        ]);
        $incomingFields['user_id'] = auth()->id();
        $appointment = Appointment::create($incomingFields);
        return response()->json([
            "success" => true,
            "data" => $appointment,
            "message" => "appointment has been created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/appointments/{appointment}',
        summary: 'Get one appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointment'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointment not found'),
        ]
    )]
    public function show(Appointment $appointment)
    {
         if(auth()->id() !== $appointment->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this appointment"
            ], 403);
        }
        return response()->json([
            "success" => true,
            "data" => $appointment,
            "message" => "appointment has been retrieved"
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
        path: '/appointments',
        summary: 'Update appointment',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['appointment_date', 'doctor_id'],
                properties: [
                    new OA\Property(property: 'appointment_date', type: 'date', format:'date'),
                    new OA\Property(property: 'status', type: 'string', enum:['pending','confirmed','cancelled'], example: 'pending or confirmed' ),
                    new OA\Property(property: 'doctor_id', type: 'integer' , example: '1'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'appointment Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function update(Request $request, Appointment $appointment)
    {
        if(auth()->id() !== $appointment->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this appointment"
            ], 403);
        }
        $incomingFields = $request->validate([
            'appointment_date' => 'sometimes|date|after:today',
            'doctor_id' => 'sometimes|exists:doctors,id' ,
            'status' => 'sometimes|string|in:pending,confirmed'
        ]);
        $appointment->update($incomingFields);
        return response()->json([
            "success" => true,
            "data" => $appointment,
            "message" => "appointment has been Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/appointments/{appointment}',
        summary: 'Delete appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointment'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointment not found'),
        ]
    )]
    public function destroy(Appointment $appointment)
    {
        if(auth()->id() !== $appointment->user_id){
            return response()->json([
                "success" => false,
                "message" => "you can't view this appointment"
            ], 403);
        }
        $appointment->status = 'cancelled';
        $appointment->save();
        return response()->json([
            "success" => true,
            "message" => "Appointment Has Been Cancelled"
        ]);
    }
}
