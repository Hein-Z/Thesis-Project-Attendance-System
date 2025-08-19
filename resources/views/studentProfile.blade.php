{{-- resources/views/students/profile.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $student->name }} - Profile</title>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/student-noti.js') }}"></script>



<!-- Load your notification script -->
<script src="{{ asset('js/noti.js') }}"></script>
    <!-- <script src="{{ asset('js/echo.js') }}"></script> -->
    

    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f5fdf6; color: #222; margin: 0; padding: 20px; }
        h2 { text-align: center; color: #1e7e34; margin-bottom: 10px; }
        .profile-header { text-align: center; margin-bottom: 20px; }
        .profile-header h3 { margin: 5px 0; }
        .summary { display: flex; justify-content: center; gap: 20px; margin-bottom: 20px; }
        .summary div { background-color: #e6f5ea; padding: 10px 20px; border-radius: 8px; border: 1px solid #c8e6cc; font-weight: 600; }
        /* reuse previous table styles */
        table.dataTable { border-collapse: collapse !important; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 8px rgba(0, 100, 0, 0.15); }
        table.dataTable thead { background-color: #28a745; color: white; }
        table.dataTable thead th { padding: 10px; text-align: center; }
        table.dataTable tbody td { padding: 8px 10px; text-align: center; }
        table.dataTable tbody tr:nth-child(even) { background-color: #f0fff4; }
        .In { color: #1e7e34; font-weight: 600; }
        .Out{ color: blue; font-weight: 600; }
        .Absent { color:  #b00020; font-weight: 600; }
        .Present { color:  #28a745; font-weight: 600; }
        .Late { color:  #ff7300ff; font-weight: 600; }
        .filters { display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; background-color: #e6f5ea; border: 1px solid #c8e6cc; border-radius: 10px; padding: 12px; box-shadow: 0 2px 5px rgba(0, 100, 0, 0.1); margin-bottom: 20px; }
        .toolbar { display: flex; align-items: center; gap: 6px; }
        .toolbar label { font-weight: 600; color: #1e7e34; }
        .filters input, .filters select, .filters button { padding: 5px 8px; border-radius: 5px; border: 1px solid #c8e6cc; outline: none; }
        #clearFilters { background-color: #28a745; color: white; font-weight: 600; cursor: pointer; border: none; transition: background 0.2s ease; }
        #clearFilters:hover { background-color: #218838; }
        #studentsTable tbody tr {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

#studentsTable tbody tr:hover {
  transform: scale(1.02); /* pop out slightly */
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  z-index: 5;
  position: relative; /* ensures shadow overlays */
}
    </style>
</head>
<body>

    <h2>{{$student->name}}'s Attendance Profile</h2>
@php
    $presentCount = $student->attendances->where('status','Present')->count();
    $inClassCount = $student->attendances->where('status','Late')->count();
    $absentCount  = $student->attendances->where('status','Absent')->count();
    $totalCount   = $presentCount + $inClassCount + $absentCount;

    $presentPercent = $totalCount ? round(($presentCount / $totalCount) * 100) : 0;
    $inClassPercent = $totalCount ? round(($inClassCount / $totalCount) * 100) : 0;
    $absentPercent  = $totalCount ? round(($absentCount / $totalCount) * 100) : 0;
@endphp

<div style="position: relative; width: 300px; height: 300px; margin: 0 auto;">
    <img src="{{ asset('images/students/'.$student->student_id.'.jpg') }}" 
         alt="Profile" 
         style="
             position: absolute;
             top: 40%;
             left: 50%;
             transform: translate(-50%, -50%);
             width: 140px;
             height: 140px;
             border-radius: 50%;
             border: 3px solid #fff;
             box-shadow: 0 2px 6px rgba(0,0,0,0.3);
             z-index: 0;
         ">

    <canvas id="attendanceChart" style="position: relative; z-index: 1;"></canvas>
</div>

<div style="display: flex; justify-content: center; gap: 20px; margin-top: 15px; font-family: Arial, sans-serif;">
    <div style="text-align: center; color: #28a745;">
        <strong>Present</strong>
        <p id="present" data-count="{{ $presentCount }}" data-percent="{{ $presentPercent }}">0 (0%)</p>
    </div>
    <div style="text-align: center; color: #ff7300ff;">
        <strong>Late</strong>
        <p id="inClass" data-count="{{ $inClassCount }}" data-percent="{{ $inClassPercent }}">0 (0%)</p>
    </div>
    <div style="text-align: center; color: #b00020;">
        <strong>Absent</strong>
        <p id="absent" data-count="{{ $absentCount }}" data-percent="{{ $absentPercent }}">0 (0%)</p>
    </div>
    <div style="text-align: center; color: #222;">
        <strong>Total</strong>
        <p id="total" data-count="{{ $totalCount }}">0</p>
    </div>
</div>

    <!-- Filter Bar -->
    <div class="filters">
        <div class="toolbar">
            <label for="dateFilter">Date:</label>
            <input type="date" id="dateFilter" />
        </div>

        <div class="toolbar">
            <label for="classFilter">Class:</label>
            <select id="classFilter"></select>
        </div>
    
        <div class="toolbar">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter"></select>
        </div>

        <button id="clearFilters" type="button">Clear Filters</button>
           <a  href="{{ url()->previous() }}"class="btn btn-secondary">Back</a>

    </div>

    <table id="studentsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Class</th>
                <th>Check In</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student->attendances as $att)
                <tr>
                   
                   
                    
                    <td>{{ $att->teacher_info->name }}-{{ $att->teacher_info->subject }}</td>
                    
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A'): '-' }}</td>
                    <td class="{{ $att->status }}">
                        {{ $att->status }}
                    </td>
                    <td data-order="{{ $att->date }} {{ $att->check_in }}">
    {{ $att->date }} 
</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    $(function () {
        var table = $('#studentsTable').DataTable({
            order: [[3, 'desc']],
            pageLength: 10
        });
     $('#dateFilter').on('change', function () {
            var selectedDate = $(this).val();
            table.column(3).search(selectedDate ? '^' + selectedDate + '$' : '', true, false).draw();
        });
       
        function buildSelect(columnIndex, $select) {
            $select.empty().append('<option value="">All</option>');
            var data = table.column(columnIndex).data().unique().sort();
            data.each(function (d) {
                var text = $('<div>').html(d).text().trim();
                if (text) $select.append('<option value="' + text + '">' + text + '</option>');
            });
            $select.on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table.column(columnIndex).search(val ? '^' + val + '$' : '', true, false).draw();
            });
        }

      
        buildSelect(0, $('#classFilter'));
      
        buildSelect(2, $('#statusFilter'));
    

       

        $('#clearFilters').on('click', function () {
            $('#dateFilter,  #statusFilter, #nameFilter, #classFilter').val('');
            table.columns().search('').draw();
        });

  var ctx = document.getElementById('attendanceChart').getContext('2d');

var attendanceChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Present', 'Late', 'Absent'],
        datasets: [{
            data: [{{ $presentCount }}, {{ $inClassCount }}, {{ $absentCount }}],
            backgroundColor: ['#28a745', '#ff7300ff', '#b00020'],
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 14 } } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let value = context.parsed;
                        let total = {{ $totalCount }};
                        let percent = total ? Math.round((value / total) * 100) : 0;
                        return context.label + ': ' + value + ' (' + percent + '%)';
                    }
                }
            }
        },
        animation: { animateRotate: true, animateScale: true }
    }
});
    });

  function animateCountWithPercent(id, duration = 1000) {
    const el = document.getElementById(id);
    const finalCount = parseInt(el.getAttribute('data-count'));
    const finalPercent = parseInt(el.getAttribute('data-percent')) || 0;
    let start = null;

    function step(timestamp) {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);

        const currentCount = Math.floor(progress * finalCount);
        const currentPercent = Math.floor(progress * finalPercent);

        if(el.hasAttribute('data-percent')){
            el.innerText = `${currentCount} (${currentPercent}%)`;
        } else {
            el.innerText = currentCount;
        }

        if(progress < 1){
            requestAnimationFrame(step);
        } else {
            // Ensure final value
            if(el.hasAttribute('data-percent')){
                el.innerText = `${finalCount} (${finalPercent}%)`;
            } else {
                el.innerText = finalCount;
            }
        }
    }

    requestAnimationFrame(step);
}

// Animate all counts on page load
window.addEventListener('DOMContentLoaded', () => {
    animateCountWithPercent('present');
    animateCountWithPercent('inClass');
    animateCountWithPercent('absent');
    animateCountWithPercent('total');
});


    </script>

</body>
</html>