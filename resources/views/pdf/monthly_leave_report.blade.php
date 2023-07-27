<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Leave Report - {{ $selectedMonth->format('F Y') }}</title>
</head>
<style>
    @page {
    header: page-header;
    footer: page-footer;
    }
    body {
    width: 100%;
    font-size: 12px;
    }
    p{
       font-size: 12px;
    }
    .col-md-6
    {
    float: left;
    width: 48%;
    /* padding: 10px; */
    }
    .col-md-8
    {
    float: left;
    width: 70%;
    }
    .col-md-10
    {
    float: left;
    width: 82%;
    }
    .col-md-12
    {
    float: left;
    width: 100%;
    }
    .col-md-2
    {
    float: left;
    width: 18%;
    }
    .col-md-3
    {
    float: left;
    width: 30%;
    }
    .col-md-1
    {
    float: left;
    width: 5%;
    }
    .bold-underline{
    border-bottom: 3px solid black;
    }
    table, td, th {  
    border: 1px solid #000000;
    text-align: left;
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }
    th, td {
    padding: 5px;
    }
    .box{
    border: 1px solid black;
    height: 20px;
    padding-left: 5px;   
    }
 </style>
<body>
    
    <h1>Monthly Leave Report - {{ $selectedMonth->format('F Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Leave Taken</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employeeLeave as $leave)
                <tr>
                    <td>{{ $leave->id }}</td>
                    <td>{{ $leave->employee->name }}</td>
                    <td>{{ $leave->employee->department }}</td>
                    <td>{{ $leave->employee->designation}}</td>
                    <td>{{ $leave->leaveType->name }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ $leave->total_leave_taken }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
