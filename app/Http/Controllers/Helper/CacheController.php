<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    /**
     * all cache types are----
     *
     * bill_estimation
     * payment_methods_list
     * institution_last_published_video
     * facilitator_last_published_video
     * student_last_watched_video
     * institution_get_courses
     * institution_get_topics
     * student_get_topics
     * student_get_courses
     * facilitator_get_topics
     * facilitator_get_courses
     * invoice_history
     * institution_get_course_published
     * institution_get_course_drafted
     * facilitator_get_course_published
     * facilitator_get_course_drafted
     * facilitator_get_students
     * student_progress
     * authority_institution_list
     */

    public function trim_cache($cache){
        return substr($cache, strpos($cache, "{") );
    }

    public function make_subscription_cache($id,$email,$data){
        Cache::put($id.'institution_subscribed'.$email, $data, $seconds = 10*60);
    }

    public function get_subscription_cache($id,$email){
        if (Cache::has($id.'institution_subscribed'.$email)) {
            return response(Cache::get($id.'institution_subscribed'.$email));
        }
        else
            return null;
    }
    public function forget_subscription_cache($id,$email){
        Cache::forget($id.'institution_subscribed'.$email);
    }

    public function make_cache($id,$email,$data,$cache_type){
        Cache::put($id.$cache_type.$email, $data, $seconds = 10*60);
    }

    public function get_cache($id,$email,$cache_type){
        if (Cache::has($id.$cache_type.$email)) {
            return response(Cache::get($id.$cache_type.$email));
        }
        else
            return null;
    }

    public function forget_cache($id,$email,$cache_type){
        Cache::forget($id.$cache_type.$email);
    }


    public function make_pagination_cache($id,$email,$data,$page_no,$cache_type){
        Cache::put($id.$cache_type.$email.'page'.$page_no, $data, $seconds = 10*60);
    }
    public function get_pagination_cache($id,$email,$page_no,$cache_type){
        if (Cache::has($id.$cache_type.$email.'page'.$page_no)) {
            return response(Cache::get($id.$cache_type.$email.'page'.$page_no));
        }
        else
            return null;
    }
    public function forget_pagination_cache($id,$email, $cache_type,$page_no){
        if (Cache::has($id.$cache_type.$email.'page'.$page_no)) {
            Cache::forget($id.$cache_type.$email.'page'.$page_no);
            $page_no++;
            return $this->forget_pagination_cache($id,$email,$cache_type,$page_no);
        }

    }

}
