<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>School Classroom Management</title>
    <!-- <script src="{{ asset('js/echo.js') }}"></script> -->
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>

<!-- Load your notification script -->
<script src="{{ asset('js/noti.js') }}"></script>
<script src="{{ asset('js/student-noti.js') }}"></script>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f4f8;
    }

    header {
      background-color: #4CAF50;
      color: white;
      padding: 20px 0;
      text-align: center;
      font-size: 28px;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .container {
      display: grid;
      /* width: 200px; */
      /* height: 100px; */
      grid-template-columns: repeat(3, 1fr); /* 3 equal columns */
  height: 70vh; /* Full height of the viewport */
      gap: 20px;
      padding: 40px;
      max-width: 1000px;
      justify-content: center;  /* center horizontally */
      align-content: center;    /* center vertically */
      margin-left:auto;
      margin-right:auto;
    }

    .class-box {
      background-color: white;
      border-radius: 10px;
      padding: 40px;
      text-align: center;
      display: flex;
  align-items: center;   /* center vertically */
  justify-content: center; /* center horizontally */
      font-size: 20px;
      font-weight: bold;
      color: #333;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.3s;
      text-decoration: none;
    }

    .class-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
      background-color: #e8f5e9;
    }

 
  </style>
</head>
<body>

  <header>
    School Classroom Management
  </header>

  <div class="container">
    <a href="http://localhost:8000/table" class="class-box">Classroom A</a>
    <a href="http://localhost:8000/table" class="class-box">Classroom B</a>
    <a href="http://localhost:8000/table" class="class-box">Classroom C</a>
  </div>

</body>
</html>