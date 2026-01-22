<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00e6e6; /* Cyan */
            --secondary-color: #8aff8a; /* Light Green */
            --text-color: #e0f2f1; /* Light Cyan */
            --bg-dark: #1a1a2e; /* Dark Blue-Purple */
            --bg-light: #16213e; /* Slightly lighter */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Share Tech Mono', monospace;
            color: var(--text-color);
            background: linear-gradient(135deg, var(--bg-dark), var(--bg-light));
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://upload.wikimedia.org/wikipedia/commons/e/e0/Webb%27s_First_Deep_Field_%28NIRCam_Image%29.jpg') no-repeat center center / cover; /* Placeholder for a space/nebula image */
            filter: blur(5px) brightness(0.7);
            opacity: 0.3;
            animation: bg-zoom-fade 60s infinite alternate ease-in-out;
            z-index: -1;
        }

        @keyframes bg-zoom-fade {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.4;
            }
            100% {
                transform: scale(1);
                opacity: 0.3;
            }
        }

        .container {
            position: relative;
            z-index: 1;
            padding: 2rem;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 30px rgba(var(--primary-color), 0.5);
            animation: container-fade-in 1.5s ease-out;
            backdrop-filter: blur(10px);
        }

        @keyframes container-fade-in {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .error-code {
            font-family: 'Orbitron', sans-serif;
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 0 20px var(--primary-color);
            margin-bottom: 1rem;
            position: relative;
            animation: glitch 2s linear infinite alternate, fade-in-up 1s ease-out;
        }

        .error-code::before,
        .error-code::after {
            content: '403';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(var(--bg-dark), 0.8);
            overflow: hidden;
            color: var(--secondary-color);
            text-shadow: 0 0 10px var(--secondary-color);
        }

        .error-code::before {
            left: 2px;
            text-shadow: -2px 0 var(--secondary-color);
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim-1 2s infinite linear alternate-reverse;
        }

        .error-code::after {
            left: -2px;
            text-shadow: -2px 0 var(--primary-color);
            clip: rect(84px, 450px, 160px, 0);
            animation: glitch-anim-2 2s infinite linear alternate-reverse;
        }

        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }

        @keyframes glitch-anim-1 {
            0% { clip: rect(78px, 9999px, 83px, 0); }
            5% { clip: rect(65px, 9999px, 27px, 0); }
            10% { clip: rect(98px, 9999px, 83px, 0); }
            15% { clip: rect(31px, 9999px, 73px, 0); }
            20% { clip: rect(99px, 9999px, 92px, 0); }
            25% { clip: rect(80px, 9999px, 87px, 0); }
            30% { clip: rect(45px, 9999px, 29px, 0); }
            35% { clip: rect(47px, 9999px, 85px, 0); }
            40% { clip: rect(100px, 9999px, 80px, 0); }
            45% { clip: rect(110px, 9999px, 120px, 0); }
            50% { clip: rect(60px, 9999px, 70px, 0); }
            55% { clip: rect(30px, 9999px, 40px, 0); }
            60% { clip: rect(90px, 9999px, 100px, 0); }
            65% { clip: rect(10px, 9999px, 20px, 0); }
            70% { clip: rect(50px, 9999px, 60px, 0); }
            75% { clip: rect(20px, 9999px, 30px, 0); }
            80% { clip: rect(80px, 9999px, 90px, 0); }
            85% { clip: rect(0px, 9999px, 10px, 0); }
            90% { clip: rect(70px, 9999px, 80px, 0); }
            95% { clip: rect(40px, 9999px, 50px, 0); }
            100% { clip: rect(10px, 9999px, 20px, 0); }
        }

        @keyframes glitch-anim-2 {
            0% { clip: rect(27px, 9999px, 99px, 0); }
            5% { clip: rect(10px, 9999px, 20px, 0); }
            10% { clip: rect(30px, 9999px, 40px, 0); }
            15% { clip: rect(50px, 9999px, 60px, 0); }
            20% { clip: rect(70px, 9999px, 80px, 0); }
            25% { clip: rect(90px, 9999px, 100px, 0); }
            30% { clip: rect(0px, 9999px, 10px, 0); }
            35% { clip: rect(20px, 9999px, 30px, 0); }
            40% { clip: rect(40px, 9999px, 50px, 0); }
            45% { clip: rect(60px, 9999px, 70px, 0); }
            50% { clip: rect(80px, 9999px, 90px, 0); }
            55% { clip: rect(100px, 9999px, 110px, 0); }
            60% { clip: rect(10px, 9999px, 20px, 0); }
            65% { clip: rect(30px, 9999px, 40px, 0); }
            70% { clip: rect(50px, 9999px, 60px, 0); }
            75% { clip: rect(70px, 9999px, 80px, 0); }
            80% { clip: rect(90px, 9999px, 100px, 0); }
            85% { clip: rect(0px, 9999px, 10px, 0); }
            90% { clip: rect(20px, 9999px, 30px, 0); }
            95% { clip: rect(40px, 9999px, 50px, 0); }
            100% { clip: rect(60px, 9999px, 70px, 0); }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            color: var(--secondary-color);
            text-shadow: 0 0 15px var(--secondary-color);
            margin-top: 0;
            animation: fade-in-up 1.2s ease-out 0.3s backwards; /* Delayed animation */
        }

        p {
            font-size: 1.2rem;
            max-width: 600px;
            line-height: 1.6;
            margin-bottom: 2rem;
            animation: fade-in-up 1.2s ease-out 0.6s backwards; /* Delayed animation */
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background: var(--primary-color);
            color: var(--bg-dark);
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px var(--primary-color);
            animation: fade-in-up 1.2s ease-out 0.9s backwards; /* Delayed animation */
        }

        .cta-button:hover {
            background: var(--secondary-color);
            color: var(--bg-dark);
            box-shadow: 0 0 25px var(--secondary-color);
            transform: translateY(-3px) scale(1.05);
        }

        .gateway {
            width: 200px;
            height: 200px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotateX(45deg);
            perspective: 1000px;
            opacity: 0.2;
            animation: gateway-animate 10s infinite alternate ease-in-out;
            pointer-events: none; /* Make sure it doesn't block clicks */
            z-index: 0; /* Behind other content */
        }

        .gateway-line {
            position: absolute;
            background: var(--primary-color);
            box-shadow: 0 0 10px var(--primary-color);
            animation: line-pulse 2s infinite alternate;
        }

        .gateway-line:nth-child(1) { top: 0; left: 0; width: 100%; height: 2px; }
        .gateway-line:nth-child(2) { top: 0; left: 0; width: 2px; height: 100%; }
        .gateway-line:nth-child(3) { top: 0; right: 0; width: 2px; height: 100%; }
        .gateway-line:nth-child(4) { bottom: 0; left: 0; width: 100%; height: 2px; }
        .gateway-line:nth-child(5) { top: 25%; left: 0; width: 100%; height: 1px; opacity: 0.5; }
        .gateway-line:nth-child(6) { top: 50%; left: 0; width: 100%; height: 1px; opacity: 0.5; }
        .gateway-line:nth-child(7) { top: 75%; left: 0; width: 100%; height: 1px; opacity: 0.5; }
        .gateway-line:nth-child(8) { top: 0; left: 25%; width: 1px; height: 100%; opacity: 0.5; }
        .gateway-line:nth-child(9) { top: 0; left: 50%; width: 1px; height: 100%; opacity: 0.5; }
        .gateway-line:nth-child(10) { top: 0; left: 75%; width: 1px; height: 100%; opacity: 0.5; }


        @keyframes gateway-animate {
            0% { transform: translate(-50%, -50%) rotateX(45deg) rotateY(0deg) scale(0.9); opacity: 0.2; }
            50% { transform: translate(-50%, -50%) rotateX(45deg) rotateY(180deg) scale(1.1); opacity: 0.3; }
            100% { transform: translate(-50%, -50%) rotateX(45deg) rotateY(360deg) scale(0.9); opacity: 0.2; }
        }

        @keyframes line-pulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; transform: scale(1.02); }
        }

    </style>
</head>
<body>
    <div class="background-animation"></div>

    <div class="gateway">
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
        <div class="gateway-line"></div>
    </div>

    <div class="container">
        <div class="error-code">403</div>
        <h1>ACCESS DENIED</h1>
        <p>
            It seems your credentials do not permit entry to this section of the system.
            Please verify your access permissions or contact your system administrator.
        </p>
        <a href="/" class="cta-button">Return to Central Hub</a>
    </div>
</body>
</html>