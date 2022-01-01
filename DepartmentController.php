<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Department;
use Validator;
use DB;
class DepartmentController extends Controller
{
    private $successStatus  =   200;
    private $errorStatus  =   400;
    private $succesMessage  =   'Success';
    private $errMessage  =   'Failed';
    private $dataExistErr  =   'Department exist already';
    private $invalidUser  =   'User not found';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('departments');
        $query = $query->select('dp_id AS department_id','dp_name AS department', 'dp_status AS status');
        $Result = $query->simplepaginate(10);
        if($Result)
        {
            return response()->json(['code' => $this->successStatus, 'status' => 'success', 'message' => $this->succesMessage, 'data' =>$Result], $this->successStatus);
        }
            return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->errMessage, 'data' =>[]], $this->errorStatus);
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
            'dp_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
        $dp_name = $request->dp_name;
        $department = new Department;
        $department->dp_name = $dp_name;
        $result = $department->save();
        if($result)
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data added successfully', $this->successStatus]);
        }
        return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->errMessage, 'data' =>[]], $this->errorStatus);
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
        $department = Department::where('dp_id',$id)->first();
        $array['department_id'] = $department->dp_id;
        $array['department'] = $department->dp_name;
        $array['status'] = $department->dp_status;
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
    {
        $validator = Validator::make($request->all(), [
            'dp_name' => 'required',
            'dp_status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
            $isDepartmentExist = Department::select("*")
            ->where('dp_name', $request->dp_name)
            ->where('dp_id', '!=' , $id)
            ->exists();
            if($isDepartmentExist)
            {
                return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->dataExistErr, 'data' =>[]], $this->errorStatus);
            }
        $UpdatingData = Department::where('dp_id',$id)->first();
        $UpdatingData->dp_name =  $request->dp_name;
        $UpdatingData->dp_status =  $request->dp_status;
        $result = $UpdatingData->save();
        if($result)
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data updated successfully', $this->successStatus]);
        }
        return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->errMessage, 'data' =>[]], $this->errorStatus);
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
    public function disable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
        $UpdatingData = Department::where('dp_id',$request->id)->first();
        $UpdatingData->dp_status = '0';
        $Result = $UpdatingData->save();
        if($Result)
            {
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data disable successfully',$this->successStatus]);
            }
            return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->errMessage, 'data' =>[]], $this->errorStatus);
    }
    public function enable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
        $UpdatingData = Department::where('dp_id',$request->id)->first();
        $UpdatingData->dp_status = '1';
        $Result = $UpdatingData->save();
        if($Result)
            {
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data enabled successfully',$this->successStatus]);
            }
            return response()->json(['code' => $this->errorStatus, 'status' => 'success', 'message' => $this->errMessage, 'data' =>[]], $this->errorStatus);
        }
}
