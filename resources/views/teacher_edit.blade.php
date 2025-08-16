{{-- resources/views/teachers/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

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

<div class="card">
    <h2>Edit Teacher Attendance</h2>

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Teacher Name</label>
            <input type="text" class="form-control" value="{{ $teacher->teacher_info?->name }}" disabled>
        </div>

        <div class="mb-3">
            <label>Subject</label>
            <input type="text" class="form-control" value="{{ $teacher->teacher_info?->subject }}" disabled>
        </div>

        <div class="mb-3">
            <label>Day</label>
            <input type="text" class="form-control" value="{{ $teacher->day }}" disabled>
        </div>

        <div class="mb-3">
    <label>Date</label>
    <input type="date" class="form-control" value="{{ $teacher->created_at->format('Y-m-d') }}" disabled>
</div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="In" {{ $teacher->status === 'In' ? 'selected' : '' }}>In</option>
                <option value="Out" {{ $teacher->status === 'Out' ? 'selected' : '' }}>Out</option>
                <option value="Absent" {{ $teacher->status === 'Absent' ? 'selected' : '' }}>Absent</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="time" class="form-control" value="{{ $teacher->time ? \Carbon\Carbon::parse($teacher->time)->format('H:i') : '' }}">
        </div>

   

        <div class="d-flex justify-content-between">
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

</body>
</html>
