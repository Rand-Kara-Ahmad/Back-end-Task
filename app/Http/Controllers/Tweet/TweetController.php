<?php

namespace App\Http\Controllers\Tweet;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TweetController extends BaseController
{
    public function __construct()
    {
        $this->changeLang(\request('lang'));
    }


    public function store(Request $request)
    {
        $code = 200;
        $rules = [
            'title' => 'required|string|min:3',
            'content_tweet' => 'required|string|max:140',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = 400;
            return $this->sendError($validator->errors(), $code);
        }
        try {

            $user_id = $request->user()->id;
            $data = $request->only(['title', 'content_tweet']);
            $data['user_id'] = $user_id;
            $res = Tweet::query()->create($data);

            return $this->sendResponseWithMessage($res, __('custom_messages.general_messages.created_success'), $code);
        } catch (\Exception $ex) {
            $code = 500;
            return $this->sendError($ex->getMessage(), $code);

        }
    }

    public function getUserTweets()
    {
        try {
            $res = Tweet::query()->where('user_id', auth()->user()->id)->paginate(2);
            return $this->sendResponseWithoutMessage($res, 200);

        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

}
