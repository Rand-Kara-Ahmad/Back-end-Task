<?php

namespace App\Http\Controllers\Follow;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FollowController extends BaseController
{

    public function __construct()
    {
        $this->changeLang(\request('lang'));
    }

    public function follow(Request $request)
    {
        $code = 200;
        $rules = [
            'follower_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = 400;
            return $this->sendError($validator->errors(), $code);
        }
        try {
            $user_id = $request->user()->id;
            $check = ['user_id' => $user_id,
                'follower_id' => $request->follower_id
            ];
            $data['user_id'] = $user_id;
            $data['follower_id'] = $request->follower_id;
            $res = Follower::query()->updateOrCreate($check, $data);

            return $this->sendResponseWithMessage($res, __('custom_messages.general_messages.created_success'), $code);
        } catch (\Exception $ex) {
            $code = 500;
            return $this->sendError($ex->getMessage(), $code);
        }
    }

    public function getFollowers()
    {
        $code = 200;
        try {
            $user_id = \request()->user()->id;
            $res = Follower::query()->where('follower_id', $user_id)->paginate(10);

            return $this->sendResponseWithoutMessage($res, $code);
        } catch (\Exception $ex) {
            $code = 500;
            return $this->sendError($ex->getMessage(), $code);
        }
    }

    public function unFollow(Request $request)
    {
        $code = 200;
        $rules = [
            'follower_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = 400;
            return $this->sendError($validator->errors(), $code);
        }
        try {
            $user_id = \request()->user()->id;
            $res = Follower::query()->where('follower_id', $user_id)->delete();

            return $this->sendResponseWithMessage($res, __('custom_messages.general_messages.unfollow_success'), $code);
        } catch (\Exception $ex) {
            $code = 500;
            return $this->sendError($ex->getMessage(), $code);
        }
    }


}
