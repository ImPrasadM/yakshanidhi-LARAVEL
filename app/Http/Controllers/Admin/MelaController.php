<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Mela;
use Auth;
use Carbon\Carbon;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MelaController extends Controller
{
 	public function showadd()
 	{
 		if($this->validate_users())
 		{
 			return view('admin.mela_add');
 		}
 	 	else
 	 	{
 	 		return redirect('/home');
 	 	}
 	}

 	public function add(Request $request)
 	{
 		$this->validate($request,[
 				'mela_name'=>'required|max:50',
 				'mela_pic'=>'required|image|mimes:jpg,jpeg,png',
 				'mela_email'=>'required',
 				'mela_contact'=>'required|min:6|max:12',
 				'mela_village'=>'required|max:50',
 				'mela_taluk'=>'required|max:50',
 				'mela_district'=>'required|max:50',
 				'mela_pin'=>'required|min:6|max:6'
 			]);
 		$mela=new Mela();

        $mela->mela_name = $request->input('mela_name');
        $mela->mela_email=$request->input('mela_email');
        $mela->contact=$request->input('mela_contact');
        $mela->village=$request->input('mela_village');
        $mela->taluk=$request->input('mela_taluk');
        $mela->district=$request->input('mela_district');
        $mela->PINCODE=$request->input('mela_pin');

		if($request->hasFile('mela_pic')) {
            $file = $request->file('mela_pic');
            //getting timestamp
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            
            $name = $timestamp. '-' .$file->getClientOriginalName();
            
            $mela->mela_pic = $name;

            $file->move(public_path().'/mela_images/', $name);
        }
     	 $mela->save();
     	 //Session::flash('success','Mela Successfully Added');
         return back()->with('success','Mela Successfully Added');
 	}
 	public function showupdate()
 	{
 		if($this->validate_users())
 		{
 			$mela=null;
 			return view('admin.mela_update',compact('mela'));
 		}
 	 	else
 	 	{
 	 		return redirect('/home');
 	 	}
 	}
 	public function insertupdate(Request $request)
 	{
 		if($this->validate_users())
 		{
 			$mela=Mela::where('mela_name','LIKE','%'.$request->input('search_key').'%')->get();
 			return view('admin.mela_update',compact('mela'));
 		}
 	 	else
 	 	{
 	 		return redirect('/home');
 	 	}
 	}  
 	public function show()
 	{
 		if($this->validate_users())
 		{	
 	 		$mela=Mela::all();
 	 		return view('admin.mela_list', compact('mela'));
 	 	}
 	 	else
 	 	{
 	 		return redirect('/home');
 	 	}
 	} 
 	public function validate_users(){
 		if(Auth::user()->hasRole('admin'))
 		{
 			return true;
 		}
 		else
 		{
 			return false;
 		}
 	}
}