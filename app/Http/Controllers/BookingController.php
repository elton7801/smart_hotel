<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function bookings(Request $request)
    {
        $query = Booking::with('room');

        // Apply Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        // Paginate the results (10 per page)
        $data = $query->paginate(10)->appends($request->query());

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
        $request->validate([
            'qrcode_content' => 'required|string|max:255',
        ], [
            'qrcode_content.required' => 'Please enter a valid room number before approving the booking.',
            'qrcode_content.string' => 'Room number must be a valid string.',
            'qrcode_content.max' => 'Room number may not be greater than 255 characters.',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->status = 'ready';
        $booking->room_number = 'Room '. $request->qrcode_content;
        $qrcodeContent = $request->input('qrcode_content', json_encode([
            'booking_id' => $booking->id,
            'start_date' => $booking->start_date,
            'end_date' => $booking->end_date,
        ]));

        $qrcode = QrCode::format('png')->size(300)->generate($qrcodeContent);
        $qrcodeBase64 = base64_encode($qrcode);
        $booking->qr_code = $qrcodeBase64 ?? null;
        $booking->save();

        $currentDate = Carbon::now();
        $startDate = Carbon::parse($booking->start_date);
        $endDate = Carbon::parse($booking->end_date);


        if ($currentDate->between($startDate, $endDate)) {
            $messageType = 'message';
            $message = 'Booking approved and QR Code generated successfully! QR is active.';
        } else {
            $messageType = 'warning';
            $message = 'Booking approved and QR Code generated. Note: The QR Code will be active between '
                . $startDate->toDateTimeString() . ' and ' . $endDate->toDateTimeString();
        }


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
        $request->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);


        $checkIn = session('check_in', $request->startDate);
        $checkOut = session('check_out', $request->endDate);

        $checkIn = Carbon::parse($checkIn)->setTime(15, 0, 0);
        $checkOut = Carbon::parse($checkOut)->setTime(12, 0, 0);

        $room = Room::findOrFail($id);

        $nights = $checkIn->diffInDays($checkOut);
        if($nights == 0){
            $nights=1;
        }

        $totalPrice = $nights * $room->price;

        $existingBookingsCount = Booking::where('room_id', $id)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('start_date', '<', $checkOut)
                      ->where('end_date', '>', $checkIn);
            })
            ->count();

        if ($existingBookingsCount >= $room->room_quantity) {
            return redirect()->back()->with('message', 'Room is fully booked for the selected dates. Please try different dates.');
        }

        if (!Auth::check()) {
            return redirect()->back()->with('message', 'You must be logged in to book a room.');
        }

        DB::beginTransaction();
        try {
            $booking = new Booking();
            $booking->room_id = $id;
            $booking->user_id = Auth::id();
            $booking->name = $request->name;
            $booking->email = $request->email;
            $booking->phone = $request->phone;
            $booking->start_date = $checkIn;
            $booking->end_date = $checkOut;
            $booking->nights = $nights;
            $booking->total_price = $totalPrice;

            if ($request->has('addon')) {
                $booking->special_request = implode(',', $request->addon);
            }

            $booking->save();
            DB::commit();

            return redirect("confirm_booking/{$booking->id}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', 'An error occurred while booking. Please try again.');
        }
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

        $bookings = Booking::whereHas('user', function ($query) use ($user) {
            $query->where('email', $user->email);
        })->with('room')->get();

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
