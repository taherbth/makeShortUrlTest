<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Requests\AuthorityGetAllInstitutionsRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorityApiDashboardController extends Controller
{
    protected $CacheController ,$authority ,$cache_type_authority_institution_list;
    public function __construct(CacheController $CacheController){
        $this->CacheController=$CacheController;
        $this->cache_type_authority_institution_list='authority_institution_list';
        $this->authority=Auth::guard('api-authority')->user();
    }
    public function get_all_institutions(AuthorityGetAllInstitutionsRequest $request){
        $cache= $this->CacheController->get_pagination_cache($this->authority->id,$this->authority->email, request()->page ,$this->cache_type_authority_institution_list);
        if($cache != null)
            return $cache;

        $string= $request->toArray()["query"];

        $institutions=Institution::whereIn('status',[0,1])
            ->OrderByDesc('id')
            ->search($string)
            ->select(['id','institution_name','email','admin_name','status','created_at']);
        $institutions->count= $institutions->count();
        $institutions=$institutions->paginate(10);


        foreach ($institutions as $institution)
            $institution->member_count=$institution->student_s()->count()+$institution->facillitor_s()->count();

        $this->CacheController->make_pagination_cache(
            $this->authority->id,
            $this->authority->email,
            $institutions,
            json_decode(json_encode($institutions))->current_page,
            $this->cache_type_authority_institution_list
        );

        return $institutions;
    }
}
