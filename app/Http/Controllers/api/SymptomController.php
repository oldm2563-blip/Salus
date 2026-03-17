<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SymptomRequest;
use App\Http\Resources\SymptomResourse;
use App\Models\Symptom;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
