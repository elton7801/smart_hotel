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
