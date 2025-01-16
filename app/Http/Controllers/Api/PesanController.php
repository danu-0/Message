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
        $pesan = Pesan::with(['user','recipient'])->get();
        $response = ApiFormatter::createJson(200, 'Get pesan successfully', $pesan);
        return response()->json($response);
    }

    public function indexById($id)
    {

        $pesan = Pesan::find($id);
        if (is_null($pesan)) {
            $response = ApiFormatter::createJson(404, 'notification not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get pesan by id successfully', $pesan);
        return response()->json($response, 200);
    }

    public function indexByUserId($id)
    {
        $pesan = Pesan::where('user_id', $id)->with('user', 'recipient')->get();
        if (is_null($pesan)) {
            $response = ApiFormatter::createJson(404, 'pesan not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get notification by user id successfully', $pesan);
        return response()->json($response, 200);
    }

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


    public function update(Request $request, Pesan $id)
    {
        try {
            if (Auth::id() !== $id->user_id) {
                $response = ApiFormatter::createJson(403, 'User unauthorized');
                return response()->json($response, 403);
            }
            $params = $request->all();
            if (isset($params['content'])) {
                $validator = Validator::make(
                    $params,
                    [
                        'content' => 'required',
                    ],
                    [
                        'content.required' => ' content is required'
                    ]
                );
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response, 400);
                }
                $data['content'] = $params['content'];
            }
            $id->update($data);
            $updatedPesan = $id->fresh();
            $response = ApiFormatter::createJson(200, 'Pesan updated successfully!', $updatedPesan);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }


    public function delete(Pesan $id)
    {
        try {
            if (Auth::id() !== $id->user_id) {
                $response = ApiFormatter::createJson(403, 'User unauthorized');
                return response()->json($response, 403);
            }
            $id->delete();
            $response = ApiFormatter::createJson(200, 'Pesan deleted successfully!');
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }


}
