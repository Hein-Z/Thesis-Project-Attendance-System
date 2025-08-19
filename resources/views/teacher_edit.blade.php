{{-- resources/views/teachers/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher Attendance</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/student-noti.js') }}"></script>

<!-- Load your notification script -->
<script src="{{ asset('js/noti.js') }}"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f5fdf6;
            padding: 40px;
        }

        .card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,100,0,0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #1e7e34;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
        }

        input, select {
            border-radius: 5px;
            border: 1px solid #c8e6cc;
            padding: 8px;
        }

        .btn-primary {
            background-color: #1e7e34;
            border: none;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background-color: #145c28;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="toast-success" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="toast-error" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                     <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            
            </div>
        </div>
    </div>
@endif
<div class="card">
    <h2>Edit Teacher Attendance</h2>

         <div class="mb-3">
            <label>Teacher Name and ID</label>
            <input type="text" class="form-control" value="{{ $teacher->teacher_info?->name }} [{{ $teacher->teacher_id }}]" disabled>
        </div>

        <div class="mb-3">
            <label>Subject</label>
            <input type="text" class="form-control" value="{{ $teacher->teacher_info?->subject }}" disabled>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="text" class="form-control" value="{{  \Carbon\Carbon::parse($teacher->check_in)->format('Y-m-d l')  }}" disabled>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" disabled>
                <option value="changed by admin" {{ $teacher->checkout_type === 'changed by admin' ? 'selected' : '' }}>"Changed by Admin</option>
                <option value="In Class" {{ $teacher->status === 'In Class' ? 'selected' : '' }}>In Class</option>
                <option value="manual" {{ $teacher->status === 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="auto" {{ $teacher->status === 'auto' ? 'selected' : '' }}>Auto</option>

            </select>
        </div>
    <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
        @csrf
          @method('PUT') 
        <div class="form-group mb-3" hidden>
            <label>Teacher ID</label>
            <input type="text" class="form-control" value="{{ $teacher->teacher_id }}" disabled>
        </div>

        <div class="form-group mb-3">
            <label>Check In Time</label>
            <input type="datetime-local" name="check_in" class="form-control" 
                   value="{{ $teacher->check_in ? \Carbon\Carbon::parse($teacher->check_in)->format('Y-m-d\TH:i') : '' }}">
        </div>

        <div class="form-group mb-3">
            <label>Check Out Time</label>
            <input type="datetime-local" name="check_out" class="form-control" 
                   value="{{ $teacher->check_out ? \Carbon\Carbon::parse($teacher->check_out)->format('Y-m-d\TH:i') : '' }}">
        </div>
<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Update Attendance</button>
    <a  href="{{ url()->previous() }}"class="btn btn-secondary">Back</a>

</div>

    </form>
       
</div>
<script src="{{asset('js/bootstrap.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var toastSuccess = document.getElementById('toast-success');
        if (toastSuccess) {
            var bsToast = new bootstrap.Toast(toastSuccess, { delay: 3000 });
            bsToast.show();
        }

        var toastError = document.getElementById('toast-error');
        if (toastError) {
            var bsToast = new bootstrap.Toast(toastError, { delay: 3000 });
            bsToast.show();
        }
    });
</script>
</body>

</html>
