<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMS | Management Portal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --brand-primary: #3b82f6; /* Trust Blue */
            --brand-success: #10b981; /* Success Green */
            --brand-accent: #8b5cf6;  /* Innovation Purple */
            --bg-deep: #0f172a;       /* Slate 900 */
        }

        body {
            background-color: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: #f8fafc;
            margin: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Subtle Geometric Background Gradient */
        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.05) 0%, transparent 40%);
            z-index: -1;
        }

        /* Refined Card Design */
        .portal-card {
            background: rgba(30, 41, 59, 0.7); /* Slate 800 */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
        }

        .portal-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .portal-card:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--accent-color);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .portal-card:hover::after {
            width: 100%;
        }

        .icon-container {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .portal-card:hover .icon-container {
            transform: scale(1.1);
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        h1, h2 {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }
    </style>
</head>
<body class="overflow-auto">

    <div class="bg-mesh"></div>

    <div class="max-w-6xl w-full px-6 py-12">
        <header class="text-center mb-20">
            <div class="inline-block px-4 py-1.5 mb-4 text-xs font-semibold tracking-widest text-blue-400 uppercase bg-blue-900/30 rounded-full border border-blue-800/50">
                Enterprise Resource Gateway
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Order Management <span class="text-blue-500">System</span>
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto font-light">
                Select your functional department to access the specialized operational interface.
            </p>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <a href="/mukhiya" class="portal-card group p-8 rounded-2xl flex flex-col items-start" style="--accent-color: var(--brand-primary);">
                <div class="icon-container w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3c1.72 0 3.347.433 4.775 1.2a10.001 10.001 0 014.225 8.8c0 1.942-.55 3.756-1.503 5.303l-.112.187m-9.245-13.2L12 7m0 0l.477-.389A2 2 0 0115.232 7.728l-.97 1.94a2 2 0 00-.096 1.707L15 15"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Mukhiya</h2>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Governance and oversight. Manage user roles, system permissions, and cross-departmental analytics.
                </p>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-500 group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Enter Admin Suite <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            </a>

            <a href="/taker" class="portal-card group p-8 rounded-2xl flex flex-col items-start" style="--accent-color: var(--brand-accent);">
                <div class="icon-container w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Taker</h2>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Customer relations and acquisition. Process new orders, manage client portfolios, and track billing.
                </p>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-500 group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Open Sales Desk <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            </a>

            <a href="/maker" class="portal-card group p-8 rounded-2xl flex flex-col items-start" style="--accent-color: var(--brand-success);">
                <div class="icon-container w-14 h-14 rounded-xl flex items-center justify-center mb-8">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Maker</h2>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Manufacturing and production. Track active jobs, inventory status, and quality control metrics.
                </p>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-500 group-hover:translate-x-1 transition-transform inline-flex items-center">
                    View Production <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </span>
            </a>

        </main>

        <footer class="mt-20 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-slate-500 text-xs tracking-wide">
            <div class="mb-4 md:mb-0">© {{ date('Y') }} OMS Enterprise. All rights reserved.</div>
            <div class="flex gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Operational Readiness: 100%
                </div>
                <div>v4.1.0-Release</div>
            </div>
        </footer>
    </div>

</body>
</html>