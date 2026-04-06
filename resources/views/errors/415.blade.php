<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>415 - Unsupported Media Type</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-red: #ef4444;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 500px;
            z-index: 2;
        }

        /* The "Wrong File" Icon */
        .icon-box {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .file-icon {
            width: 100px;
            height: 120px;
            background: var(--card-bg);
            border: 2px dashed var(--accent-red);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 3rem;
            color: var(--accent-red);
            position: relative;
            animation: shake 0.5s ease-in-out infinite alternate;
        }

        @keyframes shake {
            0% { transform: rotate(-2deg); }
            100% { transform: rotate(2deg); }
        }

        .status-badge {
            position: absolute;
            top: -10px;
            right: -20px;
            background: var(--accent-red);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        p {
            color: var(--text-dim);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .code-block {
            background: #000;
            padding: 10px;
            border-radius: 6px;
            font-family: monospace;
            color: #10b981;
            font-size: 0.9rem;
            margin: 1rem 0;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--text-main);
            color: var(--bg-color);
        }

        .btn-primary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 1px solid #334155;
            color: var(--text-dim);
        }

        .btn-outline:hover {
            background: #334155;
            color: white;
        }

        /* Background Glitch Effect */
        .glitch-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            pointer-events: none;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, #fff 2px, #fff 4px);
        }
    </style>
</head>
<body>

    <div class="glitch-bg"></div>

    <div class="container">
        <div class="icon-box">
            <div class="file-icon">?</div>
            <div class="status-badge">ERROR 415</div>
        </div>

        <h1>Format mismatch.</h1>
        
        <p>
            The server doesn't speak this language. You sent a media type that we aren't equipped to handle right now.
            <div class="code-block">Content-Type: unknown/unsupported</div>
        </p>

        <div class="btn-group">
            <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>
            <a href="/" class="btn btn-outline">Dashboard Home</a>
        </div>
    </div>

    <script>
        // Simple interactive "glitch" on mouse move
        document.addEventListener('mousemove', (e) => {
            const icon = document.querySelector('.file-icon');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            icon.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
    </script>
</body>
</html>