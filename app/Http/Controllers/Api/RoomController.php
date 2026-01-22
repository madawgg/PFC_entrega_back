<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Appointment;
use Carbon\Carbon;

class RoomController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $rooms
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'equipment' => 'nullable',     
            'place' => 'required|string|max:255'
        ]);

        try {

            $room = Room::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $room
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo guardar la habitación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       try {
            $room = Room::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $room
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la habitación',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $room = Room::findOrFail($id);

         $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'equipment' => 'sometimes|max:5000',     
            'place' => 'sometimes|string|max:255'
        ]);

        try {

            $room->update($validated);

            return response()->json([
                'status' => 'success',
                'data' => $room
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo guardar la habitación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all empty rooms for a specific date.
     */
 
    public function getEmptyRooms(Request $request)
    {
        $request->validate([
            'start'    => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);
        $duration = $request->integer('duration');
        $slotStart = Carbon::parse($request->start);
        $slotEnd   = $slotStart->copy()->addMinutes($duration);

        $occupiedRoomIds = Appointment::whereNotNull('room_id')
            ->where('status', '!=', 'cancelled')
            ->where('appointment_date', '<', $slotEnd)
            ->whereRaw(
                "DATE_ADD(appointment_date, INTERVAL duration MINUTE) > ?",
                [$slotStart]
            )
            ->pluck('room_id')
            ->unique()
            ->toArray();

        $emptyRooms = Room::whereNotIn('id', $occupiedRoomIds)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $emptyRooms,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);

        if (! $room) {
            return response()->json([
                'message' => 'Habitación no encontrado'
            ], 404);
        }

        $room->delete();

        return response()->json([
            'message' => 'HAbitación eliminada correctamente'
        ], 200);
    }
}
