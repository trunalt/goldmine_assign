<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Candidate;
use Carbon\Carbon;
use File;

class CandidateController extends Controller
{   
    public function create(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'name' => 'required|max:55|string',
            'exp_year' => 'required|required_with:exp_month',
            'exp_month' => 'required|required_with:exp_year',
            'designation' => 'required|max:55|string',
            'resume' => 'required|mimes:pdf|max:10000',
            'ctc' => 'required|not_in:0|numeric',
            'expected_ctc' => 'required|not_in:0|numeric',
            'assign' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $candidate = Candidate::create($requestData);

        if($request->hasFile('resume')){
            
            $path = public_path('resume/'.$candidate->id);
            if(!File::exists($path)) {
                Storage::makeDirectory($path);
            }

            $resumeName = Carbon::now()->timestamp.'_'.$request->file('resume')->getClientOriginalName();

            $request->file('resume')->move($path, $resumeName);
        
            $candidate->resume = $resumeName;
            $candidate->save();
        }

        return response([ 'status' => true, 'message' => 'Candidate data is successfully added.' ], 200);
    }

    public function list(Request $request){

        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $candidates = Candidate::where('assign', $request->id)
                                ->orderBy('created_at','DESC')
                                ->get();
        return response([ 'status' => true, 'list' => $candidates ], 200);


    }

    public function feedback(Request $request){

        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'id' => 'required',
            'assign' => 'required',
            'feedback' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $candidate = Candidate::where('id', $request->id)
                        ->update([
                            'feedback' => $request->feedback,
                            'status' => $request->status
                        ]);
        
        $candidates = Candidate::where('assign', $request->assign)
                        ->orderBy('created_at','DESC')
                        ->get();

        return response([ 'status' => true,
                         'list' => $candidates,
                          'message' => 'Candidate data is successfully updated' ], 200);


    }


    public function download(Request $request){

        $requestData = $request->all();
        $validator = Validator::make($requestData,[
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $candidate = Candidate::where('id', $request->id)->first();
        return response([ 'status' => true, 'list' => $candidates ], 200);


    }



}
