<?php

namespace App\Http\Controllers;
use PDF;
use Dompdf\Dompdf; // Import the Dompdf class
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LeaveController extends Controller
{
    //

    public function view_leave(Request $request)
    {
        if ($request->ajax()) {
           $data=Leave::select('employees.name as employee_name','employees.designation as designation','employees.department as department','leave_types.type as leave_type', 'leaves.*')
                ->join('employees', 'employees.id', '=', 'leaves.employee_id')
                ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
            return DataTables::of($data)
            ->addIndexColumn()          
            ->make(true);        
        }

        $employees = Employee::all();
        return view('leave.view_leave',compact('employees'));
    }

    public function create_leave()
    {
        $employee=DB::table('employees')->get();
        $leave_types=DB::table('leave_types')->get();
        return view('leave.add_leave',compact('employee','leave_types'));
    }

    public function store_leave(Request $request)
    {
            $leave = new  Leave();   
           
             // Get the duration value from the date range picker
            $duration = $request->input('duration');
            // Parse the dates using Carbon directly
            $dates = explode(' - ', $duration);
            $startDate = Carbon::createFromFormat('Y-m-d', trim($dates[0]));
            $endDate = Carbon::createFromFormat('Y-m-d', trim($dates[1]));

            $leave->employee_id = $request->employee_id;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->start_date = $startDate->format('Y-m-d');
            $leave->end_date = $endDate->format('Y-m-d');
            $totalLeaveTaken = $endDate->diffInDays($startDate) + 1; // Add 1 to include the start date itself as a leave day
            $leave->total_leave_taken = $totalLeaveTaken;
            $leave->save();

            // Update the total_leave_taken value for the employee              
            $employee = Employee::find($request->employee_id);
            $employee->intotal_leave_taken += $totalLeaveTaken;
            $employee->save();

            return redirect()->back()->with('message','Leave Added Successfully');
    }


    public function selectEmployeeAndMonth()
        {
            $employees = Employee::all();
            return view('leave.view_leave', compact('employees'));
        }

        public function generateMonthlyLeaveReport(Request $request)
        {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'selected_month' => 'required|date_format:Y-m', // The date format should be 'YYYY-MM'
            ]);
        
            $employeeId = $request->input('employee_id');
            $selectedMonth = Carbon::createFromFormat('Y-m', $request->input('selected_month'));
        
            // Fetch the individual employee's leave data for the selected month
            $employeeLeave = Leave::with('leaveType', 'employee')
                ->where('employee_id', $employeeId)
                ->where('start_date', '>=', $selectedMonth->firstOfMonth()->format('Y-m-d'))
                ->where('start_date', '<=', $selectedMonth->lastOfMonth()->format('Y-m-d'))
                ->get();
        
            // Render the PDF view manually
            $html = view('pdf.monthly_leave_report', compact('employeeLeave', 'selectedMonth'))->render();
        
            // Generate the PDF using Dompdf
            $pdf = new Dompdf();
            $pdf->loadHtml($html);
        
            // Optional: Set additional configuration for the PDF
            // $pdf->setPaper('A4', 'landscape');
        
            // Render the PDF to output
            $pdf->render();
        
            // Return the PDF for previewing in the browser
            return $pdf->stream('monthly_leave_report_' . $selectedMonth->format('F_Y') . '.pdf');
        }


  

            public function getTotalLeaveTaken($id)
            {             
                 echo json_encode(DB::table('employees')
                ->where('id', $id)->get()); 
            }

            
            public function getMaxLeaveTaken($id)
            {             
                 echo json_encode(DB::table('leave_types')
                ->where('id', $id)->get()); 
            }

}
