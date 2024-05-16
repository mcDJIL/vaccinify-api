<?php

namespace App\Http\Controllers;

use App\Models\Societies;
use App\Models\Spots;
use App\Models\Vaccinations;
use App\Models\Vaccines;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpotsController extends Controller
{

    protected $spotsModel;
    public function __construct(Spots $spots)
    {
        $this->spotsModel = $spots;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->with([ 'regional' ])->first();

        $spots = $this->spotsModel->where('regional_id', $society->regional->id)->get();

        foreach($spots as $spot) {
            //Mendapatkan data vaksin yang tersedia di spot tertentu
            $availableVaccines = $spot->vaccines()->get();

            //mendapatkan semua data vaksin
            $allVaccines = Vaccines::all();

            $availableVaccinesList = collect();

            foreach($allVaccines as $vaccine) {
                $isAvailable = $availableVaccines->contains('id', $vaccine->id);
                $availableVaccinesList->put($vaccine->name, $isAvailable);
            }

            $result[] = [
                "id" => $spot->id,
                "name" => $spot->name,
                "address" => $spot->address,
                "serve" => $spot->serve,
                "capacity" => $spot->capacity,
                "available_vaccines" => $availableVaccinesList
            ];
        }
        return response()->json(['spots' => $result]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $spot_id)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::now()->format('Y-m-d');

        $spot = $this->spotsModel->where('id', $spot_id)->first();

        $vaccination = Vaccinations::where('spot_id', $spot_id)->where('date', $date)->count();

        return response()->json([ 'date' => $date, 'spot' => $spot, 'vaccinations_count' => $vaccination ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spots $spots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spots $spots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spots $spots)
    {
        //
    }
}
