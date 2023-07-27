<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Your Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('view_leave_type')}}">Leave Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('create_employee')}}">Employee</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('view_leave')}}">Leave Report</a>
                </li>
            </ul>
        </div>
    </div>
</nav>