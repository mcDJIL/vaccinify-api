<?php

namespace App\Http\Controllers;

use App\Models\Consultations;
use App\Models\Societies;
use Illuminate\Http\Request;

class ConsultationsController extends Controller
{

    protected $consultationsModel;
    public function __construct(Consultations $consultations)
    {
        $this->consultationsModel = $consultations;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->first();

        $store = collect($request->only($this->consultationsModel->getFillable()))
        ->put('society_id', $society->id)
        ->toArray();

        $new = $this->consultationsModel->create($store);

        return response()->json([ "message" => "Request consultation sent successful" ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $token = $request->query('token');

        $society = Societies::where('login_tokens', $token)->first();

        $show = $this->consultationsModel->where('society_id', $society->id)->with([ 'doctor' ])->get();

        return response()->json([ "consultations" => $show ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultations $consultations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultations $consultations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultations $consultations)
    {
        //
    }
}
