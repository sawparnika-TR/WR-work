<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\TicketSubCategory;
use App\Models\Priority;
use App\Models\TicketCatSubPriority;
use App\Models\Department;
use App\Models\TicketRegistration;
use App\Models\TicketRegistrationHistory;
use Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    private $successStatus  =   200;
    private $errorStatus  =   400;

public function TicketRegistration(Request $request){


    $validator = Validator::make($request->all(), [

    'file' =>  'required|image|mimes:jpeg,png,jpg,gif,svg',
    'tr_subject'=> 'required',
    'tr_issue' => 'required',

    ]);
    if ($validator->fails()) {

        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
    }
    $filename = time().'.'.$request->file->extension();
    $request->file->storeAs('/public/upload',$filename);

    $tr_subject = $request->tr_subject;
    $tr_issue = $request->tr_issue;
    $user = Auth::user();
    $user_id = $user->id;

    //adding row in TicketRegistration table
     $data = new TicketRegistration;
     $data->tr_us_id = $user_id;
     $data->tr_company_us_id = '1'; //assigned user for auth user
     $data->tr_subject = $tr_subject;
     $data->tr_file = $filename;
     $data->tr_issue = $tr_issue;
     $result=$data->save();

     //Adding row in TicketRegistrationHistory table
     $history = new TicketRegistrationHistory;
     $history->trh_tr_id = $data->tr_id;
     $history->trh_tr_subject = $tr_subject;
     $history->trh_tr_issue =  $tr_issue;
     $history->trh_tr_file = $filename;
     $history->trh_us_id =  $user_id;
     $history->trh_company_us_id = $data->tr_company_us_id;
     $result=$history->save();

     if($result){
        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data stored successfully','data' => $result],$this->successStatus);
    }else{
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Data cannot stored', 'data' =>[]], $this->errorStatus);
    }
    return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);


}
public function TickectAccepted(Request $request){

    $validator = Validator::make($request->all(), [

        'tr_id' => 'required',

        ]);
        if ($validator->fails()) {

            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
        }
        $date =Carbon::now();
        $timestamp = strtotime($date);
        //$child1 = date('n.j.Y', $timestamp); // d.m.YYYY
        $time = date('H:i', $timestamp); // HH:ss
        $registered_ticket = TicketRegistration::where('tr_id',$request->tr_id)->first();
        $registered_ticket->tr_status = "2";
        $registered_ticket->tr_firstreaction_time = $time;
        $result=$registered_ticket->save();

        //new field to ticketregistrationhistory table
        $history = new TicketRegistrationHistory;
        $history->trh_tr_id = $registered_ticket->tr_id;
        $history->trh_tr_subject = $registered_ticket->tr_subject;
        $history->trh_tr_issue =  $registered_ticket->tr_issue;
        $history->trh_tr_file = $registered_ticket->tr_file;
        $history->trh_us_id =  $registered_ticket->tr_us_id;
        $history->trh_company_us_id =  $registered_ticket->tr_company_us_id;
        $history->trh_employee_remarks = "Ticket Accepted";
        $result=$history->save();
        if($result){
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Ticket Accepted ','data' => $result],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Ticket cannot Accepted', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);
}
public function TicketEscalation(Request $request){

    $validator = Validator::make($request->all(), [

        'dp_id' => 'required',
        'company_us_id' => 'required',
        'tr_id' => 'required',
        'pr_id' => 'required',
        'tcat_id' => 'required',
        'tsubcat_id' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
        }
        $tcsp = TicketCatSubPriority::where('tcsp_pr_id',$request->pr_id)
                                        ->where('tcsp_tcat_id',$request->tcat_id)
                                        ->where('tcsp_tsubcat_id',$request->tsubcat_id)
                                        ->first();
        //ticket registration updated with escalated
        $registered_ticket = TicketRegistration::where('tr_id',$request->tr_id)->first();
        $registered_ticket->tr_status = "3";
        $registered_ticket->tr_dp_id = $request->dp_id;
        $registered_ticket->tr_tcsp_id =  $tcsp->tcsp_id;
        $result= $registered_ticket->save();

        //escalation updation in RegisterationHistory
        $escalation = new TicketRegistrationHistory;
        $escalation->trh_tr_id =  $registered_ticket->tr_id;
        $escalation->trh_tr_subject = $registered_ticket->tr_subject;
        $escalation->trh_tr_issue = $registered_ticket->tr_issue;
        $escalation->trh_tr_file = $registered_ticket->tr_file;
        $escalation->trh_us_id =  $registered_ticket->tr_us_id;
        $escalation->trh_company_us_id = $request->company_us_id;
        $escalation->trh_employee_remarks = "Ticket escalated to particular Employee";
        $escalation->trh_escalation_remarks = "Ticket Escalated";
        $result=$escalation->save();

        if($result){
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Ticket Escalated','data' => $result],$this->successStatus);
        }else{
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Ticket cannot Escalated', 'data' =>[]], $this->errorStatus);
        }
        return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Something went wrong', 'data' =>[]], $this->errorStatus);

}
}
