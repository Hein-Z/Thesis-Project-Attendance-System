{{-- resources/views/teachers/profile.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}"> -->
        <link rel="stylesheet" href="{{asset('css/table.css')}}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>

    <style>
        .manual { background-color: #ffffffff !important; }
        .auto { background-color: #e6363650 !important; }
        .changed { background-color: #2ff0a657 !important; }
.in_class{background-color: #3945f044 !important;}
        h2 { text-align: center; color: #1e7e34; margin-bottom: 20px; }

        /* Filter bar styling */
        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            background-color: #e6f5ea;
            border: 1px solid #c8e6cc;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 2px 5px rgba(0, 100, 0, 0.1);
            margin-bottom: 20px;
        }
        .toolbar { display: flex; align-items: center; gap: 6px; }
        .toolbar label { font-weight: 600; color: #1e7e34; }
        .filters input, .filters select, .filters button {
            padding: 5px 8px;
            border-radius: 5px;
            border: 1px solid #c8e6cc;
            outline: none;
        }
        .filters input:focus, .filters select:focus {
            border-color: #28a745;
            box-shadow: 0 0 3px #28a74580;
        }
        #clearFilter {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s ease;
        }
        #clearFilter:hover { background-color: #218838; }

        /* Table styling */
        table.dataTable {
            border-collapse: collapse !important;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0, 100, 0, 0.15);
        }
        table.dataTable thead { background-color: #28a745; color: white; }
        table.dataTable thead th { padding: 10px; text-align: center; }
        table.dataTable tbody td { padding: 8px 10px; text-align: center; }
        table.dataTable tbody tr:nth-child(even) { background-color: #f0fff4; }

        
        .actions-cell {
    position: relative;
    width: 100px;
}

.actions {
    opacity: 0;
    display: flex;
    gap: 10px;
    justify-content: center;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateY(-10px);
}

tr:hover .actions {
    opacity: 1;
    transform: translateY(0);
}

.edit-btn, .delete-btn {
    padding: 5px 8px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s, color 0.3s, transform 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.edit-btn {
    background-color: #1e7e34;
    color: white;
}

.edit-btn:hover {
    background-color: #145c28;
    transform: scale(1.1);
}

.delete-btn {
    background-color: #b00020;
    color: white;
}

.delete-btn:hover {
    background-color: #7a0015;
    transform: scale(1.1);
}

.hidden {
  display: none;
}

#weeklyChart {
    min-width: calc(weeks_count * 50px);
}

/* Dark theme overrides */
/* Dark theme overrides */
body.dark-theme {
    background-color: #121212;
    color: #ffffff;
}

body.dark-theme table.dataTable {
    background-color: #1f1f1f;
    color: #ffffff;
}

body.dark-theme table.dataTable thead {
    background-color: #27ae60;
    color: #ffffff;
}

body.dark-theme table.dataTable tbody td {
    color: #ffffff;
}

body.dark-theme table.dataTable tbody tr:nth-child(even) {
    background-color: #2c2c2c;
}

body.dark-theme .filters {
    background-color: #1f1f1f;
    border-color: #27ae60;
}

body.dark-theme .filters label {
    color: #ffffff;
}

body.dark-theme .filters input,
body.dark-theme .filters select {
    background-color: #2c2c2c;
    color: #ffffff;
    border-color: #27ae60;
}

body.dark-theme .edit-btn {
    background-color: #27ae60;
    color: #ffffff;
}

body.dark-theme .delete-btn {
    background-color: #c0392b;
    color: #ffffff;
}

body.dark-theme #weeklyChart {
    background-color: #1f1f1f !important;
}

body.dark-theme #particlesCanvas {
    background-color: #121212;
}

/* Center the chart container */
#chartContainer {
    position: relative;
    width: 80%;
    max-width: 1000px;
    margin: 0 auto;
    height: 500px;
}


/* Animated Dark Theme Button */
.animated-btn {
    padding: 10px 25px;
    border: none;
    border-radius: 25px;
    font-weight: bold;
    cursor: pointer;
    color: #fff;
    background: linear-gradient(45deg, #27ae60, #2ecc71, #1abc9c, #16a085);
    background-size: 300% 300%;
    animation: gradientAnimation 4s ease infinite;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.animated-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

@keyframes gradientAnimation {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}
#weeklyChart {
    background: transparent !important;
}

    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Teacher Attendance Profile: {{ $teacher->name }}</h2>
@php
    $totalMinutes = $attendances->sum(function($t){
        if($t->check_in && $t->check_out){
            return \Carbon\Carbon::parse($t->check_in)
                   ->diffInMinutes(\Carbon\Carbon::parse($t->check_out));
        }
        return 0;
    });
    $totalHours = floor($totalMinutes / 60);
    $totalMins  = $totalMinutes % 60;
@endphp

<div style="text-align:center; margin-bottom:15px;">
    <strong>Total Class Time: {{ $totalHours }}h {{ $totalMins }}m</strong>
</div>
<div id="chartContainer">
    <canvas id="particlesCanvas" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index:1;"></canvas>
    <canvas id="weeklyChart" style="position: relative; z-index:2;"></canvas>
</div>


    <!-- Filters -->
    <div class="row mb-3 filters">
        <div class="col-md-3 toolbar d-flex align-items-end">
    <button id="clearFilter" class="btn btn-secondary">Clear Filter</button>
    <div style="text-align:center;">
    <button id="darkThemeToggle" class="animated-btn">🌙 Dark Theme</button>
</div>
</div>
    <div class="col-md-3 toolbar">
        <label>Start Date</label>
        <input type="date" id="start_date" class="form-control">
    </div>
    <div class="col-md-3 toolbar">
        <label>End Date</label>
        <input type="date" id="end_date" class="form-control">
    </div>
    <div class="col-md-3 toolbar" id="filterRow">
        <!-- DataTables will inject checkout_type select here -->
    </div>
    <div class="col-md-3 toolbar d-flex align-items-end">
        <button id="clearFilter" class="btn btn-secondary">Clear Filter</button>
    </div>
</div>

    <table id="attendanceTable" class="display table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class='hidden'>Time</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Duration</th>
                <th>Checkout Type</th>
               <th>Option</th>

            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr 
               @if($att->checkout_type == 'auto') 
        style="background-color: #e6363649; color: black;" 
    @elseif($att->checkout_type == 'In Class') 
        style="background-color: #1669e67a; color: black;" 
         @elseif($att->checkout_type == 'changed by admin') 
        style="background-color: #2dc6da57; color: black;" 
    @endif 
            >
                <td class="hidden">{{ $att->check_in }}</td>

                <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('y/m/d l' ) : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($att->check_in)->format('h:i a') ?? '-' }}</td>
                               <td>{{$att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i a') : '-' }}</td>
@php
    $durationText = '-';
    if($att->check_in && $att->check_out){
        $minutes = \Carbon\Carbon::parse($att->check_in)
                  ->diffInMinutes(\Carbon\Carbon::parse($att->check_out));
        $hours = floor($minutes / 60);
        $mins  = $minutes % 60;
        $durationText = ($hours > 0 ? $hours.'h ' : '') . $mins.'m';
    }
@endphp
<td>{{ $durationText }}</td>
                <td>{{ $att->checkout_type ?? '-' }}</td>
                 <td class="actions-cell">
    <div class="actions">
        <a href="{{ route('teachers.edit', $att->id) }}" data-id="{{ $att->id }}" 
        class="edit-btn" title="Edit">
            ✏️
        </a>
      
            <button type="submit" data-id="{{ $att->id }}" 
            class="delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this attendance?')">🗑️</button>
       
    </div>
</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    var table = $('#attendanceTable').DataTable({
        "order": [[0, "desc"]],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // Only apply to Checkout Type column (index 4 here)
                if (column.index() === 5) {
                    var select = $('<select id="checkout_type" class="form-control"><option value="">All</option></select>')
                        .appendTo($('#filterRow'))   // Add to a placeholder div
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    // Build dropdown options dynamically from column data
                    column.data().unique().sort().each(function (d, j) {
                        if (d) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        }
                    });
                }
            });
        },
        "rowCallback": function(row, data) {
            $(row).removeClass('manual auto changed inclass');
            let type = data[4].toLowerCase();
            if(type === 'manual') $(row).addClass('manual');
            else if(type === 'auto') $(row).addClass('auto');
            else if(type === 'changed by admin') $(row).addClass('changed');
            else if(type === 'in class') $(row).addClass('inclass');
        }
    });

    // Date range filter
    $.fn.dataTable.ext.search.push(function(settings, data) {
        let start = $('#start_date').val();
        let end = $('#end_date').val();
        let date = data[0]; // Date column

        if(start && date < start) return false;
        if(end && date > end) return false;
        return true;
    });

    $('#start_date, #end_date').on('change', function() {
        table.draw();
    });

    $('#clearFilter').on('click', function() {
        $('#start_date, #end_date').val('');
        $('#checkout_type').val('');
        table.search('').columns().search('').draw();
    });
  $('.delete-btn').click(function() {
    var teacherId = $(this).data('id');
    var row = $(this).closest('tr');

    var password = prompt("Enter password to delete:");
    if(password !== '12345678') {
        alert("Incorrect password!");
        return;
    }

    if(confirm("Are you sure you want to delete this attendance?")) {
        $.ajax({
            url: '/teachers/' + teacherId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                // Remove main row
                row.fadeOut(500, function() { $(this).remove(); });

             
            },
            error: function(xhr) {
                alert("Error deleting attendance.");
            }
        });
    }
});

  // EDIT (optional: open modal or prompt)
    $('.edit-btn').click(function(e) {
        e.preventDefault(); // Prevent default link

        var teacherId = $(this).data('id');
        var password = prompt("Enter password to edit:");
        if(password !== '12345678') {
            alert("Incorrect password!");
            return;
        }

        // Redirect to edit page
        window.location.href = '/teachers/' + teacherId + '/edit';
    });
$('#darkThemeToggle').on('click', function() {
    $('body').toggleClass('dark-theme');

    const isDark = $('body').hasClass('dark-theme');

    // Update chart colors dynamically
    myChart.options.plugins.title.color = isDark ? '#ffffff' : '#000000';
    myChart.options.scales.x.ticks.color = isDark ? '#ffffff' : '#000000';
    myChart.options.scales.y.ticks.color = isDark ? '#ffffff' : '#000000';
    myChart.options.scales.x.title.color = isDark ? '#ffffff' : '#000000';
    myChart.options.scales.y.title.color = isDark ? '#ffffff' : '#000000';
    myChart.options.plugins.tooltip.titleColor = isDark ? '#ffffff' : '#000000';
    myChart.options.plugins.tooltip.bodyColor = isDark ? '#ffffff' : '#000000';

    // Update bars gradient dynamically for dark theme
    const newGradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
    if(isDark){
        newGradient.addColorStop(0, 'rgba(46, 204, 113, 0.7)');
        newGradient.addColorStop(0.5, 'rgba(39, 174, 96, 0.8)');
        newGradient.addColorStop(1, 'rgba(22, 160, 133, 0.7)');
    } else {
        newGradient.addColorStop(0, 'rgba(72, 201, 176, 0.8)');
        newGradient.addColorStop(0.5, 'rgba(39, 174, 96, 0.9)');
        newGradient.addColorStop(1, 'rgba(22, 160, 133, 0.8)');
    }
    myChart.data.datasets[0].backgroundColor = newGradient;

    myChart.update();

    // Update particle color
    particles.forEach(p => {
        p.color = isDark ? 'rgba(46, 204, 113, 0.7)' : 'rgba(46, 204, 113, 0.5)';
    });
});

const labels = @json(array_keys($weeklyData));
const durations = @json(array_values($weeklyData)).map(mins => (mins/60).toFixed(2));

// Particle background
const particleCanvas = document.getElementById('particlesCanvas');
particleCanvas.width = particleCanvas.offsetWidth;
particleCanvas.height = particleCanvas.offsetHeight;
const pCtx = particleCanvas.getContext('2d');

const particles = [];
const particleCount = 60;

// Initialize particles
for (let i = 0; i < particleCount; i++) {
    particles.push({
        x: Math.random() * particleCanvas.width,
        y: Math.random() * particleCanvas.height,
        radius: Math.random() * 3 + 1,
        speedX: (Math.random() - 0.5) * 0.3,
        speedY: (Math.random() - 0.5) * 0.3,
        alpha: Math.random() * 0.6 + 0.2
    });
}

function drawParticles() {
    pCtx.clearRect(0, 0, particleCanvas.width, particleCanvas.height);
    particles.forEach(p => {
        pCtx.beginPath();
        pCtx.arc(p.x, p.y, p.radius, 0, Math.PI*2);
        pCtx.fillStyle = `rgba(46, 204, 113, ${p.alpha})`; // soft green
        pCtx.fill();
        p.x += p.speedX;
        p.y += p.speedY;

        if (p.x > particleCanvas.width) p.x = 0;
        if (p.x < 0) p.x = particleCanvas.width;
        if (p.y > particleCanvas.height) p.y = 0;
        if (p.y < 0) p.y = particleCanvas.height;
    });
    requestAnimationFrame(drawParticles);
}
drawParticles();

// Chart
const ctx = document.getElementById("weeklyChart").getContext("2d");

// Create dynamic gradient for bars
const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
gradient.addColorStop(0, 'rgba(72, 201, 176, 0.8)');
gradient.addColorStop(0.5, 'rgba(39, 174, 96, 0.9)');
gradient.addColorStop(1, 'rgba(22, 160, 133, 0.8)');

const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Class Hours',
            data: durations,
            backgroundColor: gradient,
            borderRadius: 12,
            barPercentage: 0.6,
            hoverBackgroundColor: 'rgba(46, 204, 113, 1)',
            hoverBorderWidth: 2,
            hoverBorderColor: '#2ecc71',
            
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        animation: {
            duration: 1800,
            easing: 'easeOutQuart'
        },
        plugins: {
            title: {
                display: true,
                text: 'Weekly Total Class Hours',
                font: { size: 22, weight: 'bold' },
                padding: { top: 20, bottom: 30 }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let totalMinutes = durations[context.dataIndex]*60;
                        let hours = Math.floor(totalMinutes / 60);
                        let mins = Math.round(totalMinutes % 60);
                        return `${hours}h ${mins}m`;
                    }
                },
                backgroundColor: 'transparent', // ADD THIS LINE
maintainAspectRatio: false,
                titleColor: '#fff',
                bodyColor: '#fff',
                bodyFont: { weight: '600' },
                padding: 12
            },
            legend: { display: false }
        },
        scales: {
            x: {
                title: { display: true, text: 'Hours', font: { weight: 'bold' } },
                beginAtZero: true
            },
            y: {
                title: { display: true, text: 'Week Range', font: { weight: 'bold' } }
            }
        }
    }
});
});
</script>

</body>
</html>
