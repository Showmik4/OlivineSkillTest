<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LeaveTypeController extends Controller
{
    public function welcome_home()
    {
        return view('LeaveType.view_leaave_type');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LeaveType::select('*');
            return DataTables::of($data)
            ->addIndexColumn()          
            ->make(true);        
        }


        return view('LeaveType.view_leaave_type');
    }


    public function create_leave_type()
    {
        return view('LeaveType.add_leave_type');
    }

    public function store_leave_type(Request $request)
    {
        

            $leave_type = new  LeaveType();              
     
            $leave_type->max_leave=$request->max_leave; 
            $leave_type->type=$request->type;          
            $leave_type->save();
            return redirect()->back()->with('message','Leave Type Added Successfully');

    }
}
