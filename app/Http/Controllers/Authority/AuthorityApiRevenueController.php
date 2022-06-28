<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorityGetRevenueDetailsRequest;
use App\Models\Institution;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AuthorityApiRevenueController extends Controller
{
    public function get_revenue_details(AuthorityGetRevenueDetailsRequest $request){

    if($request->has('query') && $request->query!=null){
        $string= $request->toArray()["query"];
        return Invoice::where('id','!=','null')
            ->search($string)
            ->select(
                'created_at',
                'institution_name',
                'admin_name',
                'total_paid'
            )
            ->paginate(7);
    }

    else{
        return Invoice::where('id','!=','null')
            ->orderByDesc('id')
            ->select(
                'created_at',
                'institution_name',
                'admin_name',
                'total_paid'
            )
            ->paginate(7);
    }

    }
}
