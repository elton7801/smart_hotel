<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Room;

use App\Models\Booking;

use App\Models\Contact;

use App\Models\Gallary;

use App\Models\User;

use Stripe;

use Session;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function room_details($id)
    {
        $room = Room::find($id);

        return view('home.room_details', compact('room'));
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


    public function contact(Request $request)
    {
        $contact = new Contact;

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();

        return redirect()->back()->with('message','Message Sent Successfully');
    }

    public function our_room()
    {
        $room = Room::all();
        return view ('home.our_room',compact('room'));
    }

    public function hotel_gallary()
    {
        $gallary = Gallary::all();
        return view ('home.hotel_gallary',compact('gallary'));
    }

    public function contact_us()
    {
        return view ('home.contact_us');
    }

    public function search_availability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        // Fetch rooms with available quantity
        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
            $query->where('start_date', '<=', $checkOut)
                ->where('end_date', '>=', $checkIn);
        })
        ->orWhereHas('bookings', function ($query) use ($checkIn, $checkOut) {
            $query->where('start_date', '<=', $checkOut)
                ->where('end_date', '>=', $checkIn)
                ->groupBy('room_id')
                ->havingRaw('COUNT(*) < rooms.room_quantity');
        })
        ->get();

        if ($availableRooms->isEmpty()) {
            return redirect()->back()->with('message', 'No rooms available for the selected dates.');
        }

        return view('home.available_room', compact('availableRooms', 'checkIn', 'checkOut'));
    }

    public function my_booking(){

        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please log in first.');
        }

        $user = Auth::user();

        if ($user->usertype !== 'user') {
            return redirect()->back()->with('message', 'Access Denied.');
        }
        $bookings = Booking::whereHas('user', function ($query) {
            $query->where('email', Auth::user()->email);
        })->with('room')->get();

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
    public function stripe($price)

    {
        return view('home.stripe', compact('price'));
    }

    public function stripePost(Request $request, $price)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Process payment
            Stripe\Charge::create([
                "amount" => $price * 100,
                "currency" => "myr",
                "source" => $request->stripeToken,
                "description" => "Room Booking payment"
            ]);

            // Fetch latest unpaid booking for the authenticated user
            $booking = Booking::where('user_id', Auth::id())
                              ->where('payment_status', 'unpaid')
                              ->latest()
                              ->first();

            if ($booking) {
                $booking->payment_status = 'paid';
                $booking->save();
            } else {
                return back()->with('error', 'No unpaid booking found.');
            }

            Session::flash('success', 'Payment successful!');
            return redirect('my_booking');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }


}
