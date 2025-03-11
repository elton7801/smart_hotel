<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Gallary;
use App\Models\Contact;
use App\Models\HousekeepingAssignment;
use App\Notifications\SendEmailNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            if($usertype == 'staff')
            {
                $user = Auth::user(); // Get only the logged-in user
                $assignments = HousekeepingAssignment::where('status', 'in progress')
                    ->where('user_id', $user->id) // Only fetch assignments for logged-in staff
                    ->get();

                return view('staff.staffPage', compact('user', 'assignments'));
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
        $data->pax_number = $request->pax_number;
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
        $data->pax_number = $request->pax_number;
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

        return redirect('message');
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

    public function add_staff()
    {
        return view('admin.add_staff');
    }

    public function register_staff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->password);
        $data->usertype = $request->usertype;

        $data->save();
        return redirect()->back()->with('success', 'Staff registered successfully!');
    }



    public function dashboard()
    {
        $rooms = Room::all();
        $galleries = Gallary::all();
        $msg = Contact::count();
        $housekeeping = HousekeepingAssignment::where('status','in progress')->count();
        $housekeepingDone = HousekeepingAssignment::where('status','done')->count();
        $housekeepingRoom = HousekeepingAssignment::where('status','in progress')->pluck('room_number');
        $totalUsers = User::where('usertype', 'user')->count();
        $totalRooms = Room::sum('room_quantity');
        $totalBookings = Booking::count();
        $totalRevenue = Booking::sum('total_price');
        $availableRooms = Room::whereDoesntHave('bookings')->count();
        $mostBookedRoom = Room::withCount('bookings')->orderByDesc('bookings_count')->first();

        // Monthly Bookings Statistics
        $monthlyStats = Booking::selectRaw('
                MONTH(start_date) as month,
                COUNT(id) as total_bookings,
                COALESCE(SUM(total_price), 0) as total_income
            ')
            ->whereYear('start_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Ensure all months are included (Jan - Dec)
        $allMonths = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
            $stat = $monthlyStats->firstWhere('month', $month);
            return [
                'month' => Carbon::create()->month($month)->format('F'),
                'total_bookings' => $stat->total_bookings ?? 0,
                'total_income' => $stat->total_income ?? 0,
            ];
        });

        // Room Bookings Count (Using JOIN with Room table)
        $roomBookings = DB::table('rooms')
            ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
            ->select('rooms.room_title', DB::raw('COUNT(bookings.id) as total_bookings'))
            ->groupBy('rooms.room_title')
            ->orderByDesc('total_bookings')
            ->get();

        $roomTitles = $roomBookings->pluck('room_title'); // Room titles
        $roomBookingCounts = $roomBookings->pluck('total_bookings'); // Number of times booked



        return view('admin.dashboard', compact(
            'rooms', 'galleries', 'totalUsers', 'totalRooms', 'totalBookings', 'totalRevenue','housekeepingRoom',
            'availableRooms', 'mostBookedRoom', 'allMonths', 'roomTitles', 'roomBookingCounts','msg','housekeeping', 'housekeepingDone'
        ));
    }

}
