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
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'pax' => 'required|integer|min:1',
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $pax = $request->pax;

        // Store search parameters in session
        session(['check_in' => $checkIn, 'check_out' => $checkOut, 'pax' => $pax]);

        // Check if there are any rooms that fit the capacity, ignoring availability
        $roomsWithEnoughCapacity = Room::where('pax_number', '>=', $pax)->exists();

        // Fetch available rooms based on capacity and booking status
        $availableRooms = Room::where('pax_number', '>=', $pax)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where(function ($subQuery) use ($checkIn, $checkOut) {
                        $subQuery->whereBetween('start_date', [$checkIn, $checkOut])
                                 ->orWhereBetween('end_date', [$checkIn, $checkOut])
                                 ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                                     $subQuery->where('start_date', '<=', $checkIn)
                                              ->where('end_date', '>=', $checkOut);
                                 });
                    });
                })
                ->orWhereHas('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->where(function ($subQuery) use ($checkIn, $checkOut) {
                        $subQuery->whereBetween('start_date', [$checkIn, $checkOut])
                                 ->orWhereBetween('end_date', [$checkIn, $checkOut])
                                 ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                                     $subQuery->where('start_date', '<=', $checkIn)
                                              ->where('end_date', '>=', $checkOut);
                                 });
                    })
                    ->groupBy('room_id')
                    ->havingRaw('COUNT(*) < (SELECT room_quantity FROM rooms WHERE rooms.id = bookings.room_id)');
                });
            })
            ->get();

        if ($availableRooms->isEmpty()) {
            if (!$roomsWithEnoughCapacity) {
                return redirect()->back()->with('message', 'No rooms available that fit the required capacity.');
            }
            return redirect()->back()->with('message', 'No rooms available for the selected dates.');
        }

        return view('home.available_room', compact('availableRooms', 'checkIn', 'checkOut', 'pax'));
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
