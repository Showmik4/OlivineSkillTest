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

             // If the user entered a search query, apply the search filter
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $data->where(function($query) use ($searchValue) {
                    $query->where('employees.name', 'like', "%{$searchValue}%")
                        ->orWhere('employees.designation', 'like', "%{$searchValue}%")
                        ->orWhere('employees.department', 'like', "%{$searchValue}%")
                        ->orWhere('leave_types.type', 'like', "%{$searchValue}%")
                        ->orWhere('start_date', 'like', "%{$searchValue}%")
                        ->orWhere('end_date', 'like', "%{$searchValue}%")
                        ->orWhere('total_leave_taken', 'like', "%{$searchValue}%");
                });
            }            
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
        $employeeId = $request->employee_id;
        $leaveTypeId = $request->leave_type_id; 
        $currentYear = date('Y'); // Get the current year  
       
        $duration = $request->input('duration');
        $dates = explode(' - ', $duration);
        $startDate = Carbon::createFromFormat('Y-m-d', trim($dates[0]));
        $endDate = Carbon::createFromFormat('Y-m-d', trim($dates[1]));
        $totalLeaveTaken = $endDate->diffInDays($startDate) + 1;

       $leaveType = DB::table('leave_types')
        ->where('id', $leaveTypeId)
        ->select('max_leave')
        ->first();

        if (!$leaveType) {
            return redirect()->back()->with('message', 'Invalid Leave Type.');
        }

        $maxLeaveAllowed = $leaveType->max_leave;

       
        $totalLeaveTakenByType = Leave::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->whereYear('start_date', $currentYear)
            ->sum('total_leave_taken');     
                   
        $newTotalLeaveTaken = $totalLeaveTakenByType + $totalLeaveTaken;

        if ($newTotalLeaveTaken > $maxLeaveAllowed) {
            return redirect()->back()->with('message', 'Total Leave Taken exceeds Max Leave. Please select a shorter duration.');
        }      
           
   
    $overlappingLeaves = Leave::where('employee_id', $employeeId)
        ->whereYear('start_date', $currentYear)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->where(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('start_date', '>=', $startDate)
                    ->where('start_date', '<=', $endDate);
            })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('end_date', '>=', $startDate)
                    ->where('end_date', '<=', $endDate);
            })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
            })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $startDate)
                    ->where('end_date', '<=', $endDate);
            })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where('start_date', '>=', $startDate)
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $endDate);
            });
        })
        ->exists();

            if ($overlappingLeaves) {
                return redirect()->back()->with('message', 'Leave overlaps with existing leaves for the same employee. Please select a different date range.');
            }

            // Check for leaves with the same start and end dates in the same year
        $duplicateLeaves = Leave::where('employee_id', $employeeId)
        ->where('start_date', $startDate->format('Y-m-d'))
        ->where('end_date', $endDate->format('Y-m-d'))
        ->whereYear('start_date', $currentYear)
        ->exists();

        if ($duplicateLeaves) {
            return redirect()->back()->with('message', 'Leave with the same date already exists for the same employee in the current year.');
        }
           
        $leave = new Leave();
        $leave->employee_id = $employeeId;
        $leave->leave_type_id = $leaveTypeId;
        $leave->start_date = $startDate->format('Y-m-d');
        $leave->end_date = $endDate->format('Y-m-d');
        $totalLeaveTaken = $endDate->diffInDays($startDate) + 1; 
        $leave->total_leave_taken = $totalLeaveTaken;
        $leave->year=$currentYear;
        $leave->save();  
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

}
