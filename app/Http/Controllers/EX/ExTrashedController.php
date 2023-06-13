<?php

namespace App\Http\EX\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;


class ExTrashedController extends Controller
{
    use ApiResponseTrait,ImageTrait;

    public function show()
    {
     $User= User::get();
     return $this->apiResponse(200,"done",null,$User);
    }
    public function showAll()
    {
        $User=  User::withTrashed()->get();
        return $this->apiResponse(200,"done",null,$User);
    }
    public function showTrashed()
    {
        $User= User::onlyTrashed()->get();
        return $this->apiResponse(200,"done",null,$User);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|Userists:Users,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $User = User::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,"done",null,$User);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|Userists:Users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $User = User::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,"done",null,$User);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|Userists:Users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $User = User::findOrFail($request->id);
        $User->delete();
        return $this->apiResponse(200,"done",null,$User);

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|Userists:Users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $User = User::onlyTrashed()->findOrFail($request->id);
        $User->restore();
        return $this->apiResponse(200,"done",null,$User);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|Userists:Users,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $User = User::onlyTrashed()->findOrFail($request->id);
        $User->forceDelete();
        return $this->apiResponse(200,"done",null,$User);
    }

}
