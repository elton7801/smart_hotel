<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function bookings()
    {
        $data=Booking::all();
        return view('admin.bookings', compact('data'));
    }

    public function delete_booking($id)
    {
        $data = booking::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function approve($id)
    {
        $data = Booking::find($id);

        return view ('admin.approve',compact('data'));
    }

    public function approve_book(Request $request, $id)
    {
        // Fetch booking record
        $booking = Booking::findOrFail($id);

        // Update status to 'approved'
        $booking->status = 'ready';

        // Get QR code content from frontend or use default
        $qrcodeContent = $request->input('qrcode_content', json_encode([
            'booking_id' => $booking->id,
            'start_date' => $booking->start_date,
            'end_date' => $booking->end_date,
        ]));

        // Generate QR Code (always generate)
        $qrcode = QrCode::format('png')->size(300)->generate($qrcodeContent);

        // Encode QR code to base64
        $qrcodeBase64 = base64_encode($qrcode);

        // Store QR code in db
        $booking->qr_code = $qrcodeBase64 ?? null;

        // Save changes
        $booking->save();

        // Check if current date is within the activation range
        $currentDate = Carbon::now();
        $startDate = Carbon::parse($booking->start_date);
        $endDate = Carbon::parse($booking->end_date);

        // Prepare message based on activation status
        if ($currentDate->between($startDate, $endDate)) {
            $messageType = 'message';
            $message = 'Booking approved and QR Code generated successfully! QR is active.';
        } else {
            $messageType = 'warning';
            $message = 'Booking approved and QR Code generated. Note: The QR Code will be active between '
                . $startDate->toDateTimeString() . ' and ' . $endDate->toDateTimeString();
        }

        // Redirect to bookings with message and QR code
        return redirect('bookings')
            ->with($messageType, $message)
            ->with('qrcode', $qrcodeBase64);
    }

    public function reject_book($id)
    {
        $booking = Booking::find($id);
        $booking->status='cancel';
        $booking->save();
        return redirect()->back();
    }

    public function add_booking(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ]);

        // Get room details (including quantity)
        $room = Room::findOrFail($id);

        // Count existing bookings for the selected room within the date range
        $existingBookingsCount = Booking::where('room_id', $id)
            ->where(function($query) use ($request) {
                $query->where('start_date', '<=', $request->endDate)
                    ->where('end_date', '>=', $request->startDate);
            })
            ->count();

        // Check if the room quantity is still available
        if ($existingBookingsCount >= $room->room_quantity) {
            return redirect()->back()->with('message', 'Room is fully booked for the selected dates. Please try different dates.');
        }

        // Create a new booking
        $data = new Booking;
        $data->room_id = $id;
        $data->user_id = Auth::id();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->start_date = $request->startDate;
        $data->end_date = $request->endDate;

        // Save the booking
        $data->save();

        return redirect("confirm_booking/{$data->id}");
    }

    public function my_booking()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please log in first.');
        }

        $user = Auth::user();

        if ($user->usertype !== 'user') {
            return redirect()->back()->with('message', 'Access Denied.');
        }

        // Fetch the user's bookings with rooms
        $bookings = Booking::whereHas('user', function ($query) use ($user) {
            $query->where('email', $user->email);
        })->with('room')->get();

        // Automatically update expired bookings to "checkout"
        foreach ($bookings as $booking) {
            if (\Carbon\Carbon::parse($booking->end_date)->isBefore(now()->startOfDay()) && $booking->status !== 'checkout') {
                $booking->status = 'checkout';
                $booking->save();
            }
        }

        return view('home.my_booking', compact('bookings'));
    }

    public function view_booking($id)
    {
        $data = Booking::find($id);
        return view('home.view_booking', compact('data'));
    }

    public function confirm_booking($id)
    {
        $data = Booking::find($id);
        return view('home.confirm_booking',compact('data'));
    }

    public function cancel_booking($id)
    {
        $data = Booking::with('room')->find($id);
        $data->delete();
        return redirect('my_booking');
    }
}
