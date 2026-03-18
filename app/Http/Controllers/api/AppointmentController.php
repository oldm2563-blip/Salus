<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Uri\Http;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            "message" => "appointment has been retrievedxx"
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
            "message" => "Appointment Has Cancelled"
        ]);
    }
}
