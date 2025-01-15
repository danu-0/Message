<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::with('user', 'contact')->get();
        $response = ApiFormatter::createJson(200, 'Get contact successfully', $contact);
        return response()->json($response);
    }

    public function indexById($id)
    {

        $contact = Contact::with('user', 'contact')->find($id);
        if (is_null($contact)) {
            $response = ApiFormatter::createJson(404, 'contact not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get contact by id successfully', $contact);
        return response()->json($response, 200);
    }


    public function indexByUserId($id)
    {
        $contact = Contact::where('user_id', $id)->with('user', 'contact')->latest()->get();
        if (is_null($contact)) {
            $response = ApiFormatter::createJson(404, 'contact not found');
            return response()->json($response, 404);
        }
        $response = ApiFormatter::createJson(200, 'Get contact by user id successfully', $contact);
        return response()->json($response, 200);
    }
    //
    //
    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make(
                $params,
                [
                    'contact_user_id' => 'required',
                ],
                [
                    'contact_user_id.required' => 'who is the user?',
                ]
            );
            if ($validator->fails()) {
                $response = ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                return response()->json($response, 400);
            }

            $contact = [
                'user_id' => Auth::id(),
                'contact_user_id' => $params['contact_user_id'],
            ];

            $data = Contact::create($contact);
            $response = ApiFormatter::createJson(201, 'create contact successfully!', $data);
            return response()->json($response, 201);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

    //
    public function delete(Contact $id)
    {
        try {
            $id->delete();
            $response = ApiFormatter::createJson(200, 'contact deleted successfully!');
            return response()->json($response, 200);
        } catch (Throwable $e) {
            $response = ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
            return response()->json($response, 500);
        }
    }

}
