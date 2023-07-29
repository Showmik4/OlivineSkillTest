<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        
        body 
        {
            background-color: white;            
        }

        .data-table td th{
          color: black;
        }
    </style>
</head>
<body>
    
@include('layout.navbar')

<div class="container-fluid px-4">
  
    <div class="card">
        <h1 class="text-center">
            <a class="btn btn-primary mt-4 text-center" href="{{ route('create_leave') }}">Add Leave</a>
        </h1>

        <div class="card-header px-5 pt-4 mb-4">    
           

            <form action="{{ route('generate_monthly_leave_report') }}" method="get" class="form-inline">
                <div class="form-group mr-2">
                    <label for="employee" class="mr-2">Select an Employee:</label>
                    <select class="form-control" name="employee_id" id="employee">
                        <option value="">Select an Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="month" class="mr-2">Select a Month:</label>
                    <input type="month" name="selected_month" id="month" class="form-control">
                </div>
                <button class="btn btn-success" type="submit">Generate PDF</button>
            </form>                     
    </div>    
    <div class="card-body">
        <div class="container">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Designation</th>   
                        <th>Department</th> 
                        <th>Leave Type</th> 
                        <th>Start Date</th> 
                        <th>End Date</th>          
                        <th>Total Leave Taken</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
      
      var table = $('.data-table').DataTable
      ({
          processing: true,
          serverSide: true,
          ajax: "{{ route('view_leave') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'employee_name', name: 'employee_name'},
              {data: 'designation', name: 'designation'}, 
              {data: 'department', name: 'department'}, 
            {data: 'leave_type', name: 'leave_type'}, 
              {data: 'start_date', name: 'start_date'}, 
              {data: 'end_date', name: 'end_date'},
              {data: 'total_leave_taken', name: 'total_leave_taken'},                     
             
            ],

            "language": {
              "search": "_INPUT_", // Placeholder for the search input
              "searchPlaceholder": "Search records...", // Placeholder text
          }
      });
      
    });
</script>    
</body>
</html>

