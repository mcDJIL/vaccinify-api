<?php

namespace App\Http\Controllers;

use App\Models\Consultations;
use App\Models\Societies;
use App\Models\Vaccinations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterVaccinationsController extends Controller
{

    protected $vaccinationsModel;
    public function __construct(Vaccinations $vaccinations)
    {
        $this->vaccinationsModel = $vaccinations;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->first();

        $vaccinations = $this->vaccinationsModel->where('society_id', $society->id)
        ->with([ 'spot', 'spot.regional', 'vaccine', 'vaccinator' ])->get();

        $result[] = [
            'vaccinations' => [
                'first' => $vaccinations->get(0),
                'second' => $vaccinations->get(1),
            ],
        ];

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date' => 'date_format:Y-m-d',
            'spot_id' => 'required'
        ]);

        if ($validation->fails()) return response()->json($validation->errors());

        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->first();
        $consultations = Consultations::where('society_id', $society->id)->where('status', 'accepted')->first();
        if (!$consultations) return response()->json([ 'message' => 'Your consultation must be accepted by doctor before' ], 401);

        $isTwoVaccine = $this->vaccinationsModel->where('society_id', $society->id)->where('dose', 2)->first();
        if ($isTwoVaccine) return response()->json([ 'message' => 'Society has been 2x vaccinated' ], 401);

        $firstVaccination = $this->vaccinationsModel->where('society_id', $society->id)->where('dose', 1)->first();
        if ($firstVaccination) {
            $secondVaccination = Carbon::parse($firstVaccination->date)->diffInDays($request->input('date'));

            if($secondVaccination >= 30) {
                $register = collect($request->only($this->vaccinationsModel->getFillable()))->put('society_id', $society->id)->put('dose', 2)->toArray();

                $new = $this->vaccinationsModel->create($register);
        
                return response()->json([ 'message' => 'Second vaccination resgitered successful' ]);
            } else {
                return response()->json([ 'message' => 'Wait at least +30 days from 1st Vaccination' ], 401);
            }
        }

        $register = collect($request->only($this->vaccinationsModel->getFillable()))->put('society_id', $society->id)->toArray();
        $new = $this->vaccinationsModel->create($register);

        return response()->json([ 'message' => 'First vaccination resgitered successful' ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
