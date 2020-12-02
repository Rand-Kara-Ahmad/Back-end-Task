<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BaseController extends Controller
{
    public function sendToken($token, $code = 200)
    {
        $response = [
            'status' => true,
            'token' => $token,
            'code' => $code
        ];
        return response()->json($response, $code);
    }

    public function sendResponseWithoutMessage($result, $code = 200)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'code' => $code
        ];
        return response()->json($response, $code);
    }

    public function sendResponseWithMessage($result, $message, $code = 200)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'message' => $message,
            'code' => $code
        ];
        return response()->json($response, $code);
    }

    public function sendError($errorMessage = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $errorMessage,
            'code' => $code
        ];
        return response()->json($response, $code);
    }

    public function uplaodImagePublicFoleder(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = '/images/users/' . time() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move('images/users/', $path);
        }
        return $path ?? null;
    }

    public function changeLang($lang)
    {
        if ($lang === 'ar') {
            App::setLocale('ar');
        } else {
            App::setLocale('en');
        }
    }
}
