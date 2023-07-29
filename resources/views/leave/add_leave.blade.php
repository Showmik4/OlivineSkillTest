<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Form</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">   
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <Style>
         .form-control {
            color: black;
    }
    </Style>
</head>
<body>
    
@include('layout.navbar')


<div class="container mt-5"> 
    <div id="error_message" class="alert alert-danger mr-3 text-center" style="display: none;">
    </div>
    @if(session('message'))
    <div class="alert alert-success mb-3">{{ session('message') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{url('store_leave')}}" method="POST" enctype="multipart/form-data" id="leave_form">
                @csrf
                <div class="form-group">
                    <label for="name">Employee Name</label>
                    <select class="form-control" name="employee_id" id="name" required>
                        <option value="">Select an Employee</option>
                        @foreach ($employee as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach                        
                    </select>
                </div>

                <div class="form-group">
                    <label for="total_leave_taken">In Total Leave Taken</label>
                    <input type="text" class="form-control" name="intotal_leave_taken" id="intotal_leave_taken" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Leave Type</label>
                    <select class="form-control" name="leave_type_id" id="leave_type" required>
                        <option value="">Select a leave type</option>
                        @foreach ($leave_types as $data)
                        <option value="{{ $data->id }}">{{ $data->type }}</option>
                        @endforeach                        
                    </select>
                </div> 
                
                <div class="form-group">
                    <label for="total_leave_taken">Max Leave Taken</label>
                    <input type="text" class="form-control" name="max_leave_taken" id="max_leave_taken" readonly>
                </div>
                
                <div class="form-group">
                    <label for="daterange">Select Leave Dates</label>
                    <input type="text" class="form-control" name="duration" id="daterange" placeholder="Select leave dates">
                </div>                                

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>




<script type="text/javascript">  
    $(document).ready(function() {
       
        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD',
            },
        });

       
        function calculateDateDuration(startDate, endDate) {
            const start = moment(startDate, 'YYYY-MM-DD');
            const end = moment(endDate, 'YYYY-MM-DD');
            return end.diff(start, 'days') + 1; 
        }

       
        $('#leave_form').on('submit', function(event) {
           
            let startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');            
            let duration = calculateDateDuration(startDate, endDate);           
            let intotalLeaveTaken = parseFloat($('#intotal_leave_taken').val());           
            let totalLeaveTaken = intotalLeaveTaken + duration;            
            let maxLeave = parseFloat($('#max_leave_taken').val());           
            if (totalLeaveTaken > maxLeave) {
                
                $('#error_message').text('Total Leave Taken exceeds Max Leave. Please select a shorter duration.');
                $('#error_message').show();

                
                event.preventDefault();
            } else {
               
                $('#error_message').hide();
            }
        });

      
        $('#name').change(function() {
            let id = $(this).val();
            $('#intotal_leave_taken').empty();
            $.ajax({
                type: 'GET',
                url: 'get_intotal_leave_taken/' + id,
                success: function(response) {
                    var response = JSON.parse(response);
                    response.forEach(element => {
                        $('#intotal_leave_taken').val(element['intotal_leave_taken']);
                    });
                }
            });
        });

       
        $('#leave_type').change(function() {
            let id = $(this).val();
            $('#max_leave_taken').empty();
            $.ajax({
                type: 'GET',
                url: 'get_max_leave_taken/' + id,
                success: function(response) {
                    var response = JSON.parse(response);
                    response.forEach(element => {
                        $('#max_leave_taken').val(element['max_leave']);
                    });
                }
            });
        });
    });
</script>
</body>
</html>