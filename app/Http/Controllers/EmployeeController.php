<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //

    
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
