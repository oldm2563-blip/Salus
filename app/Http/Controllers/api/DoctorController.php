<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index ()
    {
        $doctors = Doctor::all();
        return response()->json([
            'success' => true,
            'data' => $doctors,
            'message' => 'Doctors have been retrieved'
        ]);
    }

    public function show ($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $doctor,
            'message' => 'The doctor have been retrieved'
        ]);
    }

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
