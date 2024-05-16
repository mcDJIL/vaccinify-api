<?php

namespace App\Http\Controllers;

use App\Models\Societies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    protected $societiesModel;
    public function __construct(Societies $societies)
    {
        $this->societiesModel = $societies;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|integer',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) return response()->json($validator->errors());

        $society = $this->societiesModel->where('id_card_number', $request->input('id_card_number'))
        ->where('password', $request->input('password'))->with([ 'regional' ])->first();

        if (!$society) return response()->json([ 'message' => 'ID Card Number or Password incorrect' ], 401);

        $token = md5($society->id_card_number);

        $society->update([ 'login_tokens' => $token ]);

        return response()->json([ "data" => $society ]);
    }

    public function logout(Request $request)
    {
        $token = $request->query('token');

        $society = $this->societiesModel->where('login_tokens', $token)->first();

        if (!$society || $token === null) return response()->json([ 'message' => 'Invalid token' ]);

        $society->update([ 'login_tokens' => null ]);

        return response()->json([ "message" => 'Logout success' ]);
    }
}
