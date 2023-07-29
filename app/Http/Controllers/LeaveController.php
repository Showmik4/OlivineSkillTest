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
           
             
            $duration = $request->input('duration');          
            $dates = explode(' - ', $duration);
            $startDate = Carbon::createFromFormat('Y-m-d', trim($dates[0]));
            $endDate = Carbon::createFromFormat('Y-m-d', trim($dates[1]));

            $leave->employee_id = $request->employee_id;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->start_date = $startDate->format('Y-m-d');
            $leave->end_date = $endDate->format('Y-m-d');
            $totalLeaveTaken = $endDate->diffInDays($startDate) + 1; 
            $leave->total_leave_taken = $totalLeaveTaken;
            $leave->save();
                         
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

        function generateMonthlyLeaveReport(Request $request)
        {
            $employeeId = $request->input('employee_id');
            $selectedMonth = Carbon::createFromFormat('Y-m', $request->input('selected_month'));       
           
            $employeeLeave = Leave::with('leaveType', 'employee')
            ->where('employee_id', $employeeId)
            ->where('start_date', '>=', $selectedMonth->firstOfMonth()->format('Y-m-d'))
            ->where('start_date', '<=', $selectedMonth->lastOfMonth()->format('Y-m-d'))
            ->get();
           
            $dompdf = new Dompdf();
            $html = view('pdf.monthly_leave_report', compact('employeeLeave', 'selectedMonth'),
            [
                'mode'                 => 'utf-8',
                'format'               => 'A4-P',
                'default_font_size'    => '12',
                'default_font'         => 'FreeSerif',
                'margin_left'          => 5,
                'margin_right'         => 5,
                'margin_top'           => 5,
                'margin_bottom'        => 5,
                'margin_header'        => 0,
                'margin_footer'        => 10,
                'orientation'          => 'P',
                'title'                => 'Laravel mPDF',
                'author'               => '',
                'watermark'            => '',
                'show_watermark'       => false,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data' 	   => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa' 			=> false,
                'pdfaauto' 		=> false,
            ]); 
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            return $dompdf->stream('output.pdf', ['Attachment' => false]);
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
