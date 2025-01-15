<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class NotificationController extends Controller
{

    public function index()
    {
        $notification = Notification::with('user')->latest()->get();
        if (is_null($notification)) {
            $response = ApiFormatter::createJson(404, 'notification not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get notification successfully', $notification);
        return response()->json($response);
    }


    public function indexById($id)
    {

        $notification = Notification::find($id);
        if (is_null($notification)) {
            $response = ApiFormatter::createJson(404, 'notification not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get notification by id successfully', $notification);
        return response()->json($response, 200);
    }



    public function indexByUserId($id)
    {
        $notification = Notification::where('user_id', $id)->with('user')->latest()->get();
        if (is_null($notification)) {
            $response = ApiFormatter::createJson(404, 'notification not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get notification by user id successfully', $notification);
        return response()->json($response, 200);
    }


    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'user_id' => 'required',
                    'content' => 'required',
                ],
                [
                    'user_id.required' => 'who is the user?',
                    'content.required' => 'content is required'
                ]
            );
            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $notification = [
                'user_id' => $params['user_id'],
                'content' => $params['content'],
            ];

            $data = Notification::create($notification);
            $response = ApiFormatter::createJson(201, 'Creat notification successfully!', $data);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }


    public function update(Request $request, Notification $id)
    {
        try {
            $params = $request->all();
            if (isset($params['user_id'])) {
                $validator = Validator::make(
                    $params,
                    [
                        'user_id' => 'required',
                    ],
                    [
                        'title.required' => 'user id is required',
                    ]
                );
                if ($validator->fails()) {
                    $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                    return response()->json($response, 400);
                }
                $data['user_id'] = $params['user_id'];
            }

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
            $updatedNotification = $id->fresh();
            $response = ApiFormatter::createJson(200, 'Notification updated successfully!', $updatedNotification);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    public function delete(Notification $id)
    {
        try {
            $id->delete();
            $response = ApiFormatter::createJson(200, 'Notification deleted successfully!');
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }
}
