<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        /* Custom CSS to set the background color */
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
   
        <div class="card-header text-center">    
            <h1 class="mt-4 ">
              <a href="{{ route('create_leave_type') }}" class="btn btn-primary">Add Type</a>
    
            </h1> 
        </div>    
   
    <div class="card-body">    
      <div class="container">      
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>                   
                    <th>Type</th>            
                    <th>Maximum Leave Taken</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>    
    </div>
</div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
      
      var table = $('.data-table').DataTable
      ({
          processing: true,
          serverSide: true,
          ajax: "{{ route('view_leave_type') }}",
          columns: [
              {data: 'id', name: 'id'},             
              {data: 'type', name: 'type'}, 
              {data: 'max_leave', name: 'max_leave'},                    
             
            ]
      });
      
    });
</script>


    
</body>
</html>

