<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\ParkingPlace;
use App\User;
use App\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle = Vehicle::all();
        $parkingFeesTotalAmount = Vehicle::sum('parking_fees');

        return view('vehicle.index', compact('vehicle','parkingFeesTotalAmount'));
    }

    public function upcomingAppoinments()
    {
        $vehicle = Vehicle::whereDate('start_date', '>=', now())->get()->toArray();


        return view('vehicle.upcoming', compact('vehicle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name'     => 'required|string|max:50',
            'vehicle_number'     => 'required|string|max:50',
            'start_date'     => 'required|date',
            'end_date'     => 'required|date',
        ]);

        $response = [];

        if($validator->fails()) {
            $response['status'] = 200;
            $response['message'] = 'Validation failed!';
            $response['validator'] = $validator->errors();

            return response()
                ->json($response, 200,
                    ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_UNESCAPED_UNICODE);
        }
        $vehicle = new Vehicle();
        $vehicle->customer_name = $request->customer_name;
        $vehicle->vehicle_number = $request->vehicle_number;
        $vehicle->start_date = $request->start_date;
        $vehicle->end_date = $request->end_date;
        
        $start = Carbon::createFromFormat('Y-m-d H:s:i',$vehicle->start_date);
        $end = Carbon::createFromFormat('Y-m-d H:s:i', $vehicle->end_date);
        $diff_in_hours = $start->diffInHours($end);

        if($diff_in_hours <= 3 )
        {
            $vehicle->parking_fees = 10;
        }
        else{
            $remain_hours =  $diff_in_hours - 3;
            $vehicle->parking_fees = (10 +($remain_hours*5));
        }
        foreach(range('A','Z') as $v){
            for ($x = 1; $x <= 5; $x++) {
                $number = sprintf("%02d", $x);
                $slot = $v.$number;
                $existing_parking = Vehicle::where('slot','=',$slot)->andWhere('parking_status','=',1)->get()->toArray();
                if(empty($existing_parking))
                {
                    $vehicle->slot =  $slot; 
                }
            }
        }

        $lastAppoinmentNumber = DB::table('vehicles')->latest('id')->first();
        $lastAppoinmentSequence  = substr($lastAppoinmentNumber->appoinment_number, -3);
        if(empty($lastAppoinmentSequence))
        {
            $nextAppoinmentSequence = "AAA";
        }
        else{
            $nextAppoinmentSequence = $lastAppoinmentSequence++;
        }
            $vehicle->appoinment_number = $slot.$nextAppoinmentSequence;
        


        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $vehicle->drive_licence= $filename;
        }
        $vehicle->save();

        $response['appoinment_number'] = $vehicle->appoinment_number;
        $response['slot'] = $slot;
        $response['parking_fee'] = $vehicle->parking_fees;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($vehicle) {
                $query->where('id', $vehicle->client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Vehicle not found!';
            return $response;
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        // TODO: Dont send client
        $response['vehicle'] = $vehicle;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
   
}
