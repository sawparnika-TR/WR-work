<?php

namespace App\Http\Controllers;

use App\Models\UserDeposit;
use App\Models\BankBranch;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use App\Models\BankBranchAccount;
use App\TokenHistory;
//use Validator;
use Illuminate\Support\Facades\Validator;
//use Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDepositController extends Controller
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
            'ud_amount' => 'required|numeric',
            'ud_bank_id' => 'required',
            'ud_date' => 'required|date',
            'ud_branch' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], 400);
            }

        $user = Auth::user();
        $UserId = $user->us_id;

      $ud_amount = $request->ud_amount;
      $ud_bank_id = $request->ud_bank_id;
      $ud_date = $request->ud_date;
      $ud_bank_branch = $request->ud_branch;

      $data = new UserDeposit;
      $data->ud_us_id = $UserId;
      $data->ud_bank_id = $ud_bank_id;
      $data->ud_amount = $ud_amount;
      $data->ud_deposit_date = $ud_date;
      $data->ud_bb_id = $ud_bank_branch;
      $data->save();
      return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data added successfully']);

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserDeposit  $userDeposit
     * @return \Illuminate\Http\Response
     */
    public function show(UserDeposit $userDeposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDeposit  $userDeposit
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDeposit $userDeposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserDeposit  $userDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDeposit $userDeposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDeposit  $userDeposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDeposit $userDeposit)
    {
        //
    }

    // public function depositdetails(Request $request){

    //     $from_date = $request->from_date;
    //     $to_date = $request->to_date;
    //     $user_id = $request->user_id;
    //     $bank_id = $request->bank_id;


    //     $data = DB::table('user_deposits')->whereBetween('ud_deposit_date',[$from_date,$to_date])
    //     ->where('ud_us_id',$user_id)->where('ud_bank_id',$bank_id)->get();

    //     foreach($data as $row){

    //     $details =[];
    //     $user_name = User::where('us_id',$user_id)->value('us_fname');
    //     $shopname = User::where('us_id',$user_id)->value('us_shop_name');
    //     $bank_name = Bank::where('bk_id',$bank_id)->value('bk_bankname');

    //     $ub =[];
    //     $ub['user_name'] = $user_name;
    //     $ub['bank_name'] = $bank_name;
    //     $ub['shopname'] = $shopname;
    //     $details['deposit_date']=$row->ud_deposit_date;
    //     $details['amount']=$row->ud_amount;

    //     $result = array_merge($ub, $details);

    //     $array[]= $result;
    //     }

    //     return response()->json(['code' => 200, 'status' => 'success', 'message' => 'success', 'data' =>$array]);
    // }





    public function depositdetails(Request $request){
        $user = Auth::user();
        $UserId = $user->us_id;
        $parentIdCheck = User::where('us_id',$UserId)->first();
        $parentId = $parentIdCheck->parent_id;
        $user = $request->us_id;
        //echo "hello";exit;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $bank_id = $request->bank_id;
        $branch_id = $request->branch_id;
        //dd($branch_id);
        if($parentId == '0'){
            if(!empty($user)){
                $results =UserDeposit::whereBetween('ud_deposit_date', [$from_date, $to_date])
                            ->where('ud_us_id',$user)
                            ->where('ud_bank_id',$bank_id)
                            ->where('ud_bb_id',$branch_id)
                            ->get();
       // print_r($results); die;
        $array=[];
        $ub =[];
        foreach($results as $rs){
            $user_name = User::where('us_id',$user)->value('us_fname');
            $shopname = User::where('us_id',$user)->value('us_shop_name');
            $bank_name = Bank::where('bk_id',$bank_id)->value('bk_bankname');
            $branch = BankBranch::where('bb_id',$branch_id)
                                  ->where('bb_bk_id',$bank_id)->first();
            $account_no = BankBranchAccount::where('bba_bk_id',$bank_id)->where('bba_bb_id',$branch_id)->value('bba_ac_no');
            $ub['user_name'] = $user_name;
            $ub['bank_name'] = $bank_name;
            $ub['shopname'] = $shopname;
            $ub['account_no'] = $account_no;
            $ub['branch_name'] = $branch->bb_branch_name;
            $ub['ifsc'] = $branch->bb_ifsc;
            $ub['deposit_date']=$rs->ud_deposit_date;
            $ub['amount']=$rs->ud_amount;
            $array[]= $ub;
          }
          return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
            }
        $results =UserDeposit::whereBetween('ud_deposit_date', [$from_date, $to_date])
                               ->where('ud_bank_id',$bank_id)
                               ->where('ud_bb_id',$branch_id)->get();
        $array=[];
        $ud=[];
        foreach($results as $row){
            $user_name = User::where('us_id',$row->ud_us_id)->value('us_fname');
            $shopname = User::where('us_id',$row->ud_us_id)->value('us_shop_name');
            $bank_name = Bank::where('bk_id',$bank_id)->value('bk_bankname');
            $branch = BankBranch::where('bb_id',$branch_id)
                                  ->where('bb_bk_id',$bank_id)->first();
            $account_no = BankBranchAccount::where('bba_bk_id',$bank_id)->where('bba_bb_id',$branch_id)->value('bba_ac_no');
            $ud['user_name'] = $user_name;
            $ud['bank_name'] = $bank_name;
            $ud['shopname'] = $shopname;
            $ud['account_no'] = $account_no;
            $ud['branch_name'] = $branch->bb_branch_name;
            $ud['ifsc'] = $branch->bb_ifsc;
            $ud['deposit_date']=$row->ud_deposit_date;
            $ud['amount']=$row->ud_amount;
            $array[]= $ud;
        }
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
          }
       else{
        $results =UserDeposit::whereBetween('ud_deposit_date', [$from_date, $to_date])
                            ->where('ud_us_id',$UserId)
                            ->where('ud_bank_id',$bank_id)
                            ->where('ud_bb_id',$branch_id)
                            ->get();
       // print_r($results); die;
        $array=[];
        $ub =[];
        foreach($results as $rs){
            $user_name = User::where('us_id',$UserId)->value('us_fname');
            $shopname = User::where('us_id',$UserId)->value('us_shop_name');
            $bank_name = Bank::where('bk_id',$bank_id)->value('bk_bankname');
            $branch = BankBranch::where('bb_id',$branch_id)
                                  ->where('bb_bk_id',$bank_id)->first();
            $account_no = BankBranchAccount::where('bba_bk_id',$bank_id)->where('bba_bb_id',$branch_id)->value('bba_ac_no');
            $ub['user_name'] = $user_name;
            $ub['bank_name'] = $bank_name;
            $ub['shopname'] = $shopname;
            $ub['account_no'] = $account_no;
            $ub['branch_name'] = $branch->bb_branch_name;
            $ub['ifsc'] = $branch->bb_ifsc;
            $ub['deposit_date']=$rs->ud_deposit_date;
            $ub['amount']=$rs->ud_amount;
            $array[]= $ub;
        }
        }
        if(!empty($array))
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
    }

    public function approved($id){

        $user = Auth::user();
        $UserId = $user->us_id;
        $date = date("Y-m-d h:i:sa");


        UserDeposit::where('ud_us_id',$id)->update([

            'ud_approved_by' => $UserId,
            'ud_approved_status' => "1",
            'ud_approved_date' => $date,
         ]);
        return  response()->json(['code' => 200, 'status' => 'success', 'message' => 'admin approved']);
    }


    public function getbranch(Request $request){
        $bank_id = $request->bank_id;
        $data = BankBranch::where('bb_bk_id',$bank_id)->get();
        $array =[];
        foreach($data as $row){
            $br_id=[];
            $br_id['branch_id'] = $row->bb_id;
            $br_id['branch_name'] = $row->bb_branch_name;
            $array[] = $br_id;
        }
        if(!empty($array))
        {
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $array],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
    }

    public function getaccountdetails(Request $request){
    $bank_id = $request->bank_id;
    $branch_id = $request->branch_id;
    $data = BankBranchAccount::where('bba_bk_id',$bank_id)->where('bba_bb_id',$branch_id)->value('bba_ac_no');
    if(!empty($data))
    {
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Datas are','data' => $data],$this->successStatus);
    }else{
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'No data found', 'data' =>[]], $this->errorStatus);
    }
    return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
    }

    public function declained(Request $request){
    $id = $request->id;
    $user = Auth::user();
    $UserId = $user->us_id;
    $date = date("Y-m-d h:i:sa");
    $result=UserDeposit::where('ud_us_id',$id)->update([
        'ud_approved_by' => $UserId,
        'ud_approved_status' => "0",
        'ud_approved_date' => $date,
     ]);
    return  response()->json(['code' => 200, 'status' => 'success', 'message' => 'transaction declained']);
    }
}
