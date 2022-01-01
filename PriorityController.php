<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;
use Validator;

class PriorityController extends Controller
{
    private $successStatus  =   200;
    private $errorStatus  =   400;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $priority = Priority::all();
        $array =[];
        foreach($priority as $row){
        $arr['PRIORITY']= $row->pr_priority_name;
        $arr['RESOLUTION TIME'] = $row->pr_resolution_time;
        $arr['RESPONSE TIME'] = $row->pr_response_time;
        $array[] = $arr;
        }
        if(!empty($array))
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pr_priority_name' => 'required',
            'pr_resolution_time' => 'required',
            'pr_response_time' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
            $prioty_name = $request->pr_priority_name;
            $resolution_time = $request->pr_resolution_time;
            $response_time = $request->pr_response_time;

            $priority = new Priority;
            $priority->pr_priority_name = $prioty_name ;
            $priority->pr_resolution_time = $resolution_time;
            $priority->pr_response_time = $response_time;
            $priority->save();
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data added successfully', $this->successStatus]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $priority = Priority::where('pr_id',$id)->first();
        $array['Priority'] = $priority->pr_priority_name;
        $array['Resolution Time'] = $priority->pr_resolution_time;
        $array['Response Time'] = $priority->pr_response_time;
        if(!empty($array))
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $prioty_name = $request->pr_priority_name;
        $resolution_time = $request->pr_resolution_time;
        $response_time = $request->pr_response_time;

        $old = Priority::where('pr_id',$id)->first();
        $old->pr_priority_name = $prioty_name;
        $old->pr_resolution_time = $resolution_time;
        $old->pr_response_time = $response_time;
        $old->save();
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data updated successfully',$this->successStatus]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old = Priority::where('pr_id',$id)->first();
        $old->delete();
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data deleted successfully',$this->successStatus]);

    }

    public function disable(Request $request,$id){

        $old = Priority::where('pr_id',$id)->first();
        $old->pr_status = '0';
        $old->save();
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data disable successfully',$this->successStatus]);

    }

    public function enable(Request $request,$id){

        $old = Priority::where('pr_id',$id)->first();
        $old->pr_status = '1';
        $old->save();
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data enabled successfully',$this->successStatus]);

    }

    public function priorityList(Request $request){

        $data = Priority::all();
        $PRIORITY =[];
        foreach($data as $row){
        $array=[];
        $array = $row->pr_priority_name;
        $PRIORITY[]= $array;
        }
        return $PRIORITY;

    }

}
