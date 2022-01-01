<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\TicketSubCategory;
use App\Models\Priority;
use App\Models\TicketCatSubPriority;
use Validator;

class TicketCatSubPriorityController extends Controller
{
    private $successStatus  =   200;
    private $errorStatus  =   400;

    public function getcategory(Request $request){

       $category = TicketCategory::all();

       $array=[];
       foreach($category as $row){
        $arr['categoryid']= $row->tcat_id;
        $arr['categoryname']=$row->tcat_name;
        $array[]=$arr;
       }
       return $array;
    }

    public function getsubcategory(Request $request){

        $validator = Validator::make($request->all(), [

            'categoryid' => 'required',
            ]);
            if ($validator->fails()) {

                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }

            $categoryid = $request->categoryid;

           $data =TicketSubCategory::where('tsubcat_tcat_id',$categoryid)->get();
           $array=[];
           foreach($data as $row){
           $arr['subcategoryname']=$row->tsubcat_name;
           $arr['subcategoryid']= $row->tsubcat_id;
           $array[]=$arr;
           }
           return $array;
    }
    public function catSubcatPriority(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'priority' => 'required',
            ]);
            if ($validator->fails()) {

                return response()->json(['code' => 400, 'status' => 'error', 'message' => 'failed', 'data' => $validator->errors()], $this->errorStatus);
            }
            $decoded_subcat = json_decode($req->sub_category_id);
            $decoded_priority = json_decode($req->priority);
            if(!(count($decoded_subcat)==count($decoded_priority))){
                return "The arrays not in equal size";
            }
            else{

            $item1 = array();
            $item2 = array();
            foreach($decoded_subcat as $data)
            {
                $item1[] = $data->id;

            }

            foreach($decoded_priority as $dp)
            {
                $item2[] = $dp->id;

            }

                foreach($item1 as $key => $value) {
                   $CatSubcatPrioity = new TicketCatSubPriority;
                   $CatSubcatPrioity->tcsp_pr_id=$item2[$key];
                   $CatSubcatPrioity->tcsp_tcat_id=$req->category_id;
                   $CatSubcatPrioity->tcsp_tsubcat_id=$item1[$key];
                   $CatSubcatPrioity->save();
                }
            }
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data stored successfully'],$this->successStatus);
}
}
//
