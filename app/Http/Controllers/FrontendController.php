<?php

namespace App\Http\Controllers;

use App\Listeners\Frontend\SendMessage;
use App\Mail\Frontend\SendMessageMail;
use App\Models\Authority;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Institution;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;



class FrontendController extends Controller
{
    

    public function home()
    {        
        return view('frontend.home');
    }


    public function about()
    {
        return view('frontend.about')->withTitle(Contact::where('attribute' , "text")->firstorfail()->value);
    }


    public function blog($id = 1)
    {
        try {
            $data = Blog::findOrFail($id);
        } catch (\Throwable $th) {
            $data = Blog::take(1)->first();
        }
        $sidebar_blog = Blog::where('id', '!=', $id)->inRandomOrder()->limit(3)->get();
        $all_blog = Blog::where('id', '!=', $id)->orderBy('id', "DESC")->limit(3)->get();

        return view('frontend.blog', compact(['data', 'sidebar_blog', 'all_blog']));
    }

    public function blogList($id): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Blog::where('id', '!=', $id)->orderBy('id', "DESC")->paginate(3);
    }


    public function faq()
    {
        $data = Faq::all();
        return view('frontend.faq', compact(['data']));
    }
    public function mail(Request $data)
    {
        $data->validate([
            'name' => "required|string",
            'email' => "required|email",
            'message' => "required|string"
        ]);

        Mail::to(
            Contact::where('attribute' , "notify-email")
                ->pluck('value')
                ->toArray()
        )->queue(
            new SendMessageMail(
                $data->name,
                $data->email,
                $data->message
            )
        );

        return redirect()->back()->with('success', 'Message send successfully');
    }


    public function contact()
    {
        $data = [];
        $contact_list =  Contact::all();
        foreach ($contact_list as $key => $item) {
            $data[$item->attribute] = $item->value;
        }

        return view('frontend.contact', compact('data'));
    }

    public function tnc()
    {
        $data =Contact::where('attribute','tnc')->first();
        return view('frontend.tnc', compact('data'));
    }
}
