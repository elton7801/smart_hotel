<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\HousekeepingAssignment;


use Illuminate\Http\Request;

class staffController extends Controller
{

    public function assign_housekeeping(Request $request)
    {
        // Validate input structure
        $request->validate([
            'assignments' => 'required|array',
        ]);

        foreach ($request->assignments as $userId => $times) {
            foreach ($times as $timeSlot => $data) {
                // Extract room number & addons safely
                $roomNumber = $data['room_number'] ?? null;
                $addons = isset($data['addon']) ? implode(',', $data['addon']) : null;

                if (!empty($roomNumber)) {
                    // Retrieve existing assignment if it exists
                    $existingAssignment = HousekeepingAssignment::where('user_id', $userId)
                        ->where('time_slot', $timeSlot)
                        ->first();

                    // Determine if an update is needed
                    if (!$existingAssignment ||
                        $existingAssignment->room_number !== $roomNumber ||
                        $existingAssignment->special_request !== $addons) {

                        HousekeepingAssignment::updateOrCreate(
                            [
                                'user_id' => $userId,
                                'time_slot' => $timeSlot,
                            ],
                            [
                                'room_number' => $roomNumber,
                                'special_request' => $addons,
                                'status' => 'in progress', // Always reset to 'in progress' when updated
                            ]
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Housekeeping assignments updated successfully!');
    }

    public function viewHousekeepingAssignments()
    {
        $users = User::where('usertype', 'staff')->get();
        $assignments = HousekeepingAssignment::all();

        return view('admin.view_staff', compact('users', 'assignments'));
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
