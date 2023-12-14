<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoServer Intro</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #000000;
        }

        .container1 {
            position: relative;
            width: 100%;
            height: 100vh;
            background-color: #000000;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
            font-size: 100px;
        }

        .sliding-bar {
            position: absolute;
            width: 0;
            height: 200px;
            background: linear-gradient(to right, #000000, #1a3b9e, #000000);
            text-shadow: 20px;
            animation: slideBar 1s ease-in-out forwards;
        }

        .white-light {
            position:relative;
            width: 100%;
            height: 200px;
            background-color: white;
            opacity: 0;
            animation: fadeIn 4s ease-in-out forwards;
        }

        @keyframes slideBar {
            0% {
                width: 0;
            }
            100% {
                width: 100%;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .intro-text {
            position: absolute;
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="white-light"></div>
        <div class="sliding-bar">
        
        </div>
        
        <div class="intro-text">InfoServer</div>
        
    </div>
</body>
</html>
