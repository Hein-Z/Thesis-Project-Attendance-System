<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Timetable & Attendance</title>
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/student-noti.js') }}"></script>

<!-- Load your notification script -->
<script src="{{ asset('js/noti.js') }}"></script>
  <style>
    body {
      margin: 0;
      padding: 20px;
      background: #f9fafb;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 30px;
    }

    h2 {
      color: #111827;
    }

    table {
      width: 90%;
      border-collapse: collapse;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      border: 1px solid #d1d5db;
      padding: 12px;
      text-align: center;
    }

    th {
      background-color: #1e7e34;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f3f4f6;
    }

    td a {
      text-decoration: none;
      color: #111827;
      font-weight: bold;
    }

    td a:hover {
      color: #3b82f6;
      text-decoration: underline;
    }

    .button-column {
      display: flex;
      flex-direction: vertical;
      gap: 20px;
      width: 500px; /* Increased width */
      height: 80px;
      animation: fadeIn 1s forwards;
      text-align: center;
    }

    .cardBtn {
      padding: 18px;
      border-radius: 12px;
      text-align: center;
      font-weight: bold;
      color: #fff;
      text-decoration: none;
      font-size: 1.1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease, background 1s ease;
    }

    .cardBtn:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .teachers {
      background: linear-gradient(135deg, #60a5fa, #a78bfa);
      background-size: 200% 200%;
      animation: gradientShift 6s ease infinite;
    }

    .students {
      background: linear-gradient(135deg, #34d399, #facc15);
      background-size: 200% 200%;
      animation: gradientShift 6s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      table {
        width: 100%;
      }
      .button-column {
        width: 100%;
      }
    }

    
    /* Fullscreen overlay */
        #loading-screen {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at center, #2d4739, #1c2e25);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s ease;
            color: #fff;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        /* Book icon animation (open/close effect) */
        .book-icon {
            font-size: 80px;
            animation: bookOpenClose 2s ease-in-out infinite alternate;
        }

        @keyframes bookOpenClose {
            0% { transform: rotateY(0deg); }
            50% { transform: rotateY(15deg); }
            100% { transform: rotateY(0deg); }
        }

        /* Pencil icon moving effect */
        .pencil-icon {
            font-size: 40px;
            color: #f1c40f;
            margin-top: 20px;
            animation: pencilMove 2s linear infinite alternate;
        }

        @keyframes pencilMove {
            0% { transform: translateX(-40px) rotate(-20deg); }
            100% { transform: translateX(40px) rotate(-20deg); }
        }

        /* Chalkboard glowing text */
        .loading-text {
            margin-top: 40px;
            font-size: 28px;
            font-weight: bold;
            color: #fff;
            animation: chalkGlow 1.5s ease-in-out infinite alternate;
        }

        @keyframes chalkGlow {
            from { text-shadow: 0 0 10px #fff, 0 0 20px #b4ffb4, 0 0 30px #00ff00; }
            to { text-shadow: 0 0 20px #fff, 0 0 30px #33ff33, 0 0 40px #00cc00; }
        }
  </style>
</head>
<body>
   <div id="loading-screen">
        <i class="fas fa-book book-icon"></i>
        <i class="fas fa-pencil pencil-icon"></i>
        <div class="loading-text">Loading School Classroom Management...</div>
    </div>
    <div id="app">
  <h2>School Timetable</h2>

  <table>
    <tr>
      <th>Day</th>
      <th>8:00 - 9:00</th>
      <th>9:00 - 10:00</th>
      <th>10:00 - 11:00</th>
      <th>11:00 - 12:00</th>
    </tr>
    <tr><td>Monday</td><td><a href="#">English</a></td><td><a href="#">Math</a></td><td><a href="#">Physics</a></td><td><a href="#">Chemistry</a></td></tr>
    <tr><td>Tuesday</td><td><a href="#">English</a></td><td><a href="#">Math</a></td><td><a href="#">Physics</a></td><td><a href="#">Chemistry</a></td></tr>
    <tr><td>Wednesday</td><td><a href="#">English</a></td><td><a href="#">Math</a></td><td><a href="#">Physics</a></td><td><a href="#">Chemistry</a></td></tr>
    <tr><td>Thursday</td><td><a href="#">English</a></td><td><a href="#">Math</a></td><td><a href="#">Physics</a></td><td><a href="#">Chemistry</a></td></tr>
    <tr><td>Friday</td><td><a href="#">English</a></td><td><a href="#">Math</a></td><td><a href="#">Physics</a></td><td><a href="#">Chemistry</a></td></tr>
  </table>

  <div class="button-column">
    <a class="cardBtn teachers" href="http://localhost:8000/teachers">Teachers' Attendance Table</a>
    <a class="cardBtn students" href="http://localhost:8000/students">Students' Attendance Table</a>
  </div>
</div>
<script>
     // Keep loader for exactly 8 seconds
        window.addEventListener("load", () => {
            setTimeout(() => {
                document.getElementById("loading-screen").style.opacity = "0";
                setTimeout(() => {
                    document.getElementById("loading-screen").style.display = "none";
                    document.getElementById("app").style.opacity = "1";
                }, 800); // fade out transition
            }, 3000); // 8 seconds delay
        });
</script>
</body>
</html>
