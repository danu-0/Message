<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormatter;
use Throwable;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = Pesan::with('User', 'recipient')->get();
        return response()->json(['status' => 'success', 'data' => $pesan]);
    }

    // public function indexByUser($user_id)
    // {
    //     $pesan = Pesan::where('user_id', $user_id)->orWhere('recipient_id', $user_id)->with('user', 'recipient')->get();
    //     return response()->json(['status' => 'success', 'data' => $pesan]);
    // }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'recipient_id' => 'required',
                    'content' => 'required',
                ],
                [
                    'recipient_id.required' => 'who is the recipient?',
                    'content.required' => 'content is required'
                ]
            );
            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $pesan = [
                'user_id' => Auth::id(),
                'recipient_id' => $params['recipient_id'],
                'content' => $params['content'],
            ];

            $data = Pesan::create($pesan);
            $response = ApiFormatter::createJson(201, 'Sending Message successfully!', $data);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }


}
