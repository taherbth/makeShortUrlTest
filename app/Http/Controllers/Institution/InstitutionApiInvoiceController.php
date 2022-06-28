<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\CourseController;
use App\Models\Authority;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionApiInvoiceController extends Controller
{
    protected $stripe;
    protected $CourseController;
    protected $CacheController;
    protected $cache_type_invoice_history;
    public function __construct(CourseController $CourseController, CacheController $CacheController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->CourseController = $CourseController;
        $this->CacheController = $CacheController;
        $this->cache_type_invoice_history = 'invoice_history';
    }

    public function get_all_invoice_history(Request $request){
        $pagination_per_user=10;
        $institution=Institution::find(Institution::return_id());
        if($institution->stripe_id == null){
            return response(null);
        }
        if(!$request->has('page')){
            $cache= $this->CacheController->get_pagination_cache($institution->id,$institution->email,1,$this->cache_type_invoice_history);
        }else{
            $cache= $this->CacheController->get_pagination_cache($institution->id,$institution->email,$request->page,$this->cache_type_invoice_history);
        }
        if ($cache != null){

            $trimmed_cache=$this->CacheController->trim_cache($cache);
            //$charges= $this->CourseController->paginate($trimmed_cache, $perPage = 10, $page = null, $options = []);
            return response($trimmed_cache);
        }
        else{
            $data=$this->stripe->charges->all(['customer'=>$institution->stripe_id])->data;
            $charges= $this->CourseController->paginate($data, $perPage = $pagination_per_user, $page = null, $options = []);
            $charges_obj=json_decode(json_encode($charges));
            $number_of_pages=$charges_obj->total/$pagination_per_user;
            $this->CacheController->make_pagination_cache($institution->id,$institution->email,$charges,$charges_obj->current_page,$this->cache_type_invoice_history);
            return response($charges);
        }
    }
}
