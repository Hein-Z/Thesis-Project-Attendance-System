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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


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
       <!-- Loading Screen -->
    <div id="loading-screen">
        <i class="fas fa-book book-icon"></i>
        <i class="fas fa-pencil pencil-icon"></i>
        <div class="loading-text">Loading School Classroom Management...</div>
    </div>
<div id="app">
  <header>
    School Classroom Management
  </header>

  <div class="container">
    <a href="http://localhost:8000/table" class="class-box">Classroom A</a>
    <a href="http://localhost:8000/table" class="class-box">Classroom B</a>
    <a href="http://localhost:8000/table" class="class-box">Classroom C</a>
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