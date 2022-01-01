<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Countryoperator;
use App\Models\Servicecategory;
use App\Models\Servicesubcategory;
use App\Models\Operator;
use App\Models\PlanType;

class ExcelValidationController extends Controller
{
    private $ErrVar = true;
    private $errcd = '';
   private function validateDatas($array){

        $count=1;

        foreach($array as $r){

            if($r['name'] != NULL){

                $count++;
                $isCountryExist = Country::select("*")
                ->where("cu_name", $r['name'])
                    ->exists();

                if($isCountryExist)
                {
                 $this->ErrVar = false;
                 $this->errcd = 'Country already exist';
                 return array('row'=>$count,'message'=>$this->errcd);
                }

            }

        }
        return array('row'=>NULL,'message'=>'No errors');
    }

   private function validateDatasOperators($array){

    $count=1;
    foreach($array as $r){

        if($r['name'] != NULL){

            $count++;
            $isOperatorExist = Operator::select("*")
            ->where("op_name", $r['name'])
                ->exists();

            if($isOperatorExist)
            {
             $this->ErrVar = false;
             $this->errcd = 'operator already exist';
            return array('row'=>$count,'message'=>$this->errcd);
            }

        }

 }
return array('row'=>NULL,'message'=>'No errors');
}

function validateDatasCountryOperators($array){

    $count=1;
    foreach($array as $r){
        if($r['country_name'] != NULL){
            $count++;
            $isCountryExist = Country::select("*")
                            ->where("cu_name", $r['country_name'])
                            ->doesntExist();
            if($isCountryExist)
            {
                $this->ErrVar = false;
                $this->errcd = 'Country does not exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }

            $isOperatorExist =Operator::select("*")
                             ->where("op_name", $r['operator_name'])
                             ->doesntExist();
            if($isOperatorExist)
                {
                    $this->ErrVar = false;
                    $this->errcd = 'Operator Does not exist';
                    return array('row'=>$count,'message'=>$this->errcd);
                }
            $country = Country::where('cu_name', $r['country_name'])->first();
            $operator = Operator::where('op_name',$r['operator_name'])->first();
            $isCountryOperator = Countryoperator::where('co_country_id',$country->cu_id)
                                                ->where('co_operator_id',$operator->op_id)
                                                ->exists();

            if($isCountryOperator){
                $this->ErrVar = false;
                $this->errcd = 'CountryOperator already exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }

    }
    return array('row'=>NULL,'message'=>'No errors');
}



}
//
function validateDatasService($array)
    {
        $count = 1;

        foreach ($array as $r) {

            if ($r['country'] != NULL) {

                $count++;
                $isCountryExist = Country::select("*")
                    ->where("cu_name", $r['country'])
                    ->doesntExist();

                if ($isCountryExist) {
                    $this->ErrVar = false;
                    $this->errcd = 'country does not exist';
                    return array('row' => $count, 'message' => $this->errcd);
                }
                $country = Country::where('cu_name', $r['country'])->first();
                $isServiceCategoryExist = Servicecategory::select('*')
                                        ->where('sc_cuid', $country->cu_id)
                                        ->where('sc_name', $r['category'])->exists();

                if ($isServiceCategoryExist) {
                    $this->ErrVar = false;
                    $this->errcd = 'service category already exist';
                    return array('row' => $count, 'message' => $this->errcd);
                }
            }
        }
        return array('row' => NULL, 'message' => 'No errors');
    }
    function validateDatasServiceSubCat($array){
        $count=1;

               foreach($array as $r){

                   if($r['name'] != NULL){

                       $count++;
                       $isCountryExist = Country::select("*")
                                       ->where("cu_name",$r['country'])
                                       ->doesntExist();
                       if( $isCountryExist){
                           
                           $this->ErrVar = false;
                           $this->errcd = 'country does not exists';
                           return array('row'=>$count,'message'=>$this->errcd);
                       }
                       $country = Country::where('cu_name', $r['country'])->first();
                       $isServiceCategoryExist = Servicecategory::select('*')
                                               ->where("sc_cuid",$country->cu_id)
                                               ->where("sc_name",$r['category'])
                                               ->doesntExist();
                       if($isServiceCategoryExist){

                           $this->ErrVar = false;
                           $this->errcd = 'servicecategory does not exists';
                           return array('row'=>$count,'message'=>$this->errcd);
                       }

                       $servicecategory = Servicecategory::where('sc_cuid',$country->cu_id)
                                         ->where('sc_name',$r['category'])->first();


                       $isServicesubCategoryExist = Servicesubcategory::select("*")
                                                   ->where('ssc_cuid',$country->cu_id)
                                                   ->where('ssc_scid',$servicecategory->sc_id)
                                                   ->where('ssc_name',$r['name'])
                                                   ->exists();

                       if($isServicesubCategoryExist)
                       {
                        $this->ErrVar = false;
                        $this->errcd = 'servicesubcategory already exists';
                        return array('row'=>$count,'message'=>$this->errcd);
                       }

                   }

               }
               return array('row'=>NULL,'message'=>'No errors');
   }
   function validateDatasCurrency($array){

    $count=1;

    foreach($array as $r){

        if($r['currency_name'] != NULL){

            $count++;
            $isCountryExist = Country::select("*")
                            ->where("cu_name", $r['country'])
                            ->doesntExist();
            if($isCountryExist)
            {
                $this->ErrVar = false;
                $this->errcd = 'Country Does not exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }
            $country = Country::where('cu_name', $r['country'])->first();
            $isCurrencyExist =Currency::select("*")
                        ->where('cur_cuid',$country->cu_id)
                        ->where("cur_name", $r['currency_name'])
                        ->exists();

                if($isCurrencyExist)
                {
                    $this->ErrVar = false;
                    $this->errcd = 'Currency already exist';
                    return array('row'=>$count,'message'=>$this->errcd);
                }

        }

    }
    return array('row'=>NULL,'message'=>'No errors');
}

function validateDatasPlantype($array){
    $count=1;
    foreach($array as $r){
        if($r['name'] != NULL){
            $count++;
            $isCountryExist = Country::select('*')
            ->where("cu_name",$r['country'])
            ->doesntExist();
            if($isCountryExist){
                $this->ErrVar = false;
                $this->errcd = 'country does not exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }
           $country = Country::where('cu_name', $r['country'])->first();
            $isOperatorExist = Operator::select('*')
            ->where("op_name",$r['operator'])
            ->doesntExist();
            if($isOperatorExist){
                $this->ErrVar = false;
                $this->errcd = 'operator does not exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }
            $operator = Operator::where('op_name', $r['operator'])->first();
            $iscountryoperatorExist = CountryOperator::select('*')
                                    ->where('co_country_id',$country->cu_id)
                                    ->where('co_operator_id',$operator->op_id)
                                    ->doesntExist();
            if($iscountryoperatorExist){
                $this->ErrVar = false;
                $this->errcd = 'Countryoperator does not exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }
            $isplanTypeExist = Plantype::select("*")
                            ->where('plt_cuid',$country->cu_id)
                            ->where('plt_opid',$operator->op_id)
                            ->where('plt_name', $r['name'])
                            ->exists();
            if($isplanTypeExist)
            {
                $this->ErrVar = false;
                $this->errcd = 'plantype already exist';
                return array('row'=>$count,'message'=>$this->errcd);
            }
        }
    }
    return array('row'=>NULL,'message'=>'No errors');
}


}
