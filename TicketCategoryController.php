<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCategory;
use Validator;
class TicketCategoryController extends Controller
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
        $category = TicketCategory::all();
        $array =[];

        foreach($category as $row){

            $arr['TicketCategory']= $row->tcat_name;
            $arr['TicketCtegory_id']=$row->tcat_id;
            $array[]=$arr;
        }
        if(!empty($array))
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);


        //
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
            'tcat_name' => 'required',

            ]);
            if ($validator->fails()) {

                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
            $categoryname = $request->tcat_name;
            $category = new TicketCategory;
            $category->tcat_name =$categoryname;
            $result = $category->save();
            if($result){
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data stored successfully','data' => $result],$this->successStatus);
            }else{
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data cannot stored', 'data' =>[]], $this->errorStatus);
            }
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
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
            $category = TicketCategory::where('tcat_id',$id)->first();
            $array['TicketCategory'] = $category->tcat_name;
            if(!empty($array))
            {
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
            }else{
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
            }
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    $validator = Validator::make($request->all(), [
        'tcat_name' => 'required',

        ]);
        if ($validator->fails()) {

            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
        }
        $ticketcategory = $request->tcat_name;
        $old = TicketCategory::where('tcat_id',$id)->first();
        $old->tcat_name = $ticketcategory;
        $result=$old->save();
        if($result){
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data updated successfully','data' => $result],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data cannot updated', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function disable(Request $request,$id){

        $old = TicketCategory::where('tcat_id',$id)->first();
        $old->tcat_status = '0';
        $result = $old->save();
        $result=$old->save();
        if($result){
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data disable successfully','data' => $result],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data cannot disabled', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);

    }
    public function enable(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
        $old = TicketCategory::where('tcat_id',$request->id)->first();
        $old->tcat_status = '1';
        $result = $old->save();
        if($result){
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data enabled successfully','data' => $result],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data cannot enabled', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);


    }

}
