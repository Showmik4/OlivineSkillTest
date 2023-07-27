<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    
@include('layout.navbar')


<div class="container mt-5"> 

    @if(session('message'))
    <div class="alert alert-success mb-3">{{ session('message') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{url('store_leave_type')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" class="form-control" id="type" required>
                        <option value="">Select Leave Type</option>
                        <option value="monthly">Monthly</option>
                        <option value="semi-annual">Semi Annual</option>
                        <option value="annual">Annual</option>                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="max_leave_taken">Maximum Leave Taken</label>
                    <input type="text" class="form-control" name="max_leave" id="max_leave" required placeholder="Enter  Maximum Leave Taken">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    
</body>
</html>