<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\HousekeepingAssignment;


use Illuminate\Http\Request;

class staffController extends Controller
{

    public function assign_housekeeping(Request $request)
    {
        // Validate input
        $request->validate([
            'assignments' => 'required|array',
        ]);

        foreach ($request->assignments as $userId => $times) {
            foreach ($times as $timeSlot => $roomNumber) {
                if (!empty($roomNumber)) { // Ensure a room number is provided
                    $assignment = HousekeepingAssignment::where('user_id', $userId)
                        ->where('time_slot', $timeSlot)
                        ->first();

                        if ($assignment) {
                            // Check if the room number has changed
                            if ($assignment->room_number !== $roomNumber) {
                                $assignment->update([
                                    'room_number' => $roomNumber,
                                    'status' => 'in progress', // Change status to 'in progress' only for updated entries
                                ]);
                            }
                        } else {
                            // Create new assignment if it doesn't exist
                            HousekeepingAssignment::create([
                                'user_id' => $userId,
                                'room_number' => $roomNumber,
                                'time_slot' => $timeSlot,
                                'status' => 'in progress',
                            ]);
                        }

                }
            }
        }

        // Fetch updated data
        $users = User::where('usertype', 'staff')->get();
        $assignments = HousekeepingAssignment::whereIn('status', ['in progress', 'done'])->get();

        return view('admin.view_staff', compact('users', 'assignments'))
            ->with('success', 'Housekeeping assignments updated successfully!');
    }

    public function roomsToClean()
    {
        $user = auth()->user();

        if (!$user || $user->usertype !== 'staff') {
            abort(403, 'Unauthorized access');
        }

        $assignments = HousekeepingAssignment::where('status', 'in progress')
            ->where('user_id', $user->id) // Ensure this is filtering properly
            ->get();

        return view('staff.staffPage', compact('assignments'));
    }

    public function doneClean($id)
    {
        $data = HousekeepingAssignment::find($id);
        $data->status='done';
        $data->save();

        return redirect('staffPage');
    }

}
