<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Gallary;
use App\Models\Contact;
use App\Notifications\SendEmailNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Notification;

class AdminController extends Controller
{
    public function index()
    {
        if(Auth::id())
        {
            $usertype = Auth()->user()->usertype;

            if($usertype == 'user')
            {
                $room = Room::all();
                $gallary = Gallary::all();
                return view('home.index',compact('room','gallary'));
            }

            if($usertype == 'admin')
            {
                $user = User::all();
                return view('admin.index', compact('user'));
            }
            else
            {
                return redirect()->back();
            }
        }
    }

    public function home()
    {
        $room = Room::all();
        $gallary = Gallary::all();

        return view('home.index',compact('room','gallary'));
    }

    public function create_room()
    {
        return view('admin.create_room');
    }

    public function add_room(Request $request)
    {
        $data = new Room();
        $data->room_title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->room_quantity = $request->room_quantity;
        $data->wifi = $request->wifi;
        $data->room_type = $request->type;
        $image=$request->image;

            if($image)
            {
                $imagename=time().'.'.$image->getClientOriginalExtension();

                $request->image->move('room',$imagename);

                $data->image=$imagename;
            }
        $data->save();
        return redirect()->back();
    }

    public function view_room()
    {
        $data = Room::all();

        return view ('admin.view_room',compact('data'));
    }

    public function delete_room($id)
    {
        $data = Room::find($id);

        $data->delete();

        return redirect()->back();
    }

    public function edit_room($id)
    {
        $data = Room::find($id);

        return view('admin.edit_room',compact('data'));
    }

    public function update_room(Request $request, $id)
    {
        $data = Room::find($id);

        $data->room_title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->room_quantity = $request->room_quantity;
        $data->wifi = $request->wifi;
        $data->room_type = $request->type;

        $image=$request->image;

        if($image)
        {
            $imagename=time().'.'.$image->getClientOriginalExtension();

            $request->image->move('room',$imagename);

            $data->image=$imagename;
        }

        $data->save();

        return redirect()->back();
    }

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
        $booking->status = 'approved';

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
        $booking->status='reject';
        $booking->save();
        return redirect()->back();
    }

    public function view_gallary()
    {
        $gallary= Gallary::all();
        return view('admin.gallary',compact('gallary'));
    }

    public function upload_gallary(Request $request)
    {
        $data = new Gallary;

        $image = $request->image;

        if($image)
        {
            $imagename=time().'.'.$image->getclientOriginalExtension();

            $request->image->move('gallary',$imagename);

            $data->image = $imagename;

            $data->save();

            return redirect()->back();
        }
    }

    public function delete_gallary($id)
    {
        $data = Gallary::find($id);

        $data->delete();

        return redirect()->back();
    }

    public function message()
    {
        $data = Contact::all();
        return view('admin.message', compact('data'));
    }

    public function send_mail($id)
    {
        $data = Contact::find($id);
        return view('admin.send_mail',compact('data'));
    }

    public function mail(Request $request, $id)
    {
        $data = Contact::find($id);

        $details = [
            'greeting' => $request->greeting,
            'body' => $request->body,
            'action_text' => $request->action_text,
            'action_url' => $request->action_url,
            'endline' => $request->endline,
        ];

        Notification::send($data, new SendEmailNotification($details));

        return redirect()->back();
    }

    public function show_qr()
    {
        return view('home.qrcode');
    }

    public function generate_qr(Request $request)
    {
        if ($request->qrcode) {

            // Fetch the record with start_date and end_date
            $record = Booking::where('qr_code', $request->qrcode)->first();

            if (!$record) {
                return back()->with('error', 'QR code data not found in the database.');
            }

            $currentDate = Carbon::now();
            $startDate = Carbon::parse($record->start_date);
            $endDate = Carbon::parse($record->end_date);

            // Check if current date is between start and end date
            if ($currentDate->between($startDate, $endDate)) {

                // Generate QR code
                $qrcode = QrCode::format('png')->size(300)->generate($request->qrcode);

                // Encode QR code to base64 for storing in DB
                $qrcodeBase64 = base64_encode($qrcode);

                // Update the qr_code column in the database
                $record->qr_code = $qrcodeBase64;
                $record->save();

                return back()->with('qrcode', $qrcodeBase64)->with('message', 'QR Code generated and stored successfully!');
            } else {
                return back()->with('error', 'QR Code is inactive. Valid between ' . $startDate->toDateTimeString() . ' and ' . $endDate->toDateTimeString());
            }
        }

        return back()->with('error', 'Please provide QR code data.');
    }
}
