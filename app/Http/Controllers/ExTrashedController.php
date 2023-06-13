<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ex;

use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;


class ExTrashedController extends Controller
{
    use ApiResponseTrait,ImageTrait;

    public function show()
    {
     $Ex= EX::get();
     return $this->apiResponse(200,"done",null,$Ex);
    }
    public function showAll()
    {
        $Ex=  EX::withTrashed()->get();
        return $this->apiResponse(200,"done",null,$Ex);
    }
    public function showTrashed()
    {
        $Ex= EX::onlyTrashed()->get();
        return $this->apiResponse(200,"done",null,$Ex);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:Exs,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Ex = Ex::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,"done",null,$Ex);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:Exs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Ex = Ex::withTrashed()->findOrFail($request->id);
        return $this->apiResponse(200,"done",null,$Ex);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:Exs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $Ex = Ex::findOrFail($request->id);
        $Ex->delete();
        return $this->apiResponse(200,"done",null,$Ex);

    }
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:Exs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $Ex = EX::onlyTrashed()->findOrFail($request->id);
        $Ex->restore();
        return $this->apiResponse(200,"done",null,$Ex);
    }
    public function forceDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:Exs,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, 'validation error', $validator->errors());
        }
        $Ex = Ex::onlyTrashed()->findOrFail($request->id);
        $Ex->forceDelete();
        return $this->apiResponse(200,"done",null,$Ex);
    }

}
