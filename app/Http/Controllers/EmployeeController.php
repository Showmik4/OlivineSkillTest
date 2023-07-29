<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Employee::select('*');
            return DataTables::of($data)
            ->addIndexColumn()          
            ->make(true);        
        }
        return view('employee.view_employee');
    }

    
    public function create_employee()
    {
        return view('employee.add_employee');
    }

    public function store_employee(Request $request)
    {
            $employee = new  Employee();              
            $employee->name=$request->name; 
            $employee->designation=$request->designation; 
            $employee->department=$request->department;          
            $employee->save();
            return redirect()->back()->with('message','Employee Added Successfully');
    }
}
