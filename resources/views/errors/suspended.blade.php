<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Revoked | System Neural Link</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        .glow-text {
            text-shadow: 0 0 15px rgba(239, 68, 68, 0.7);
        }

        .bg-cyber {
            background: radial-gradient(circle at center, #0a0a12 0%, #000000 100%);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .scanner-line {
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, transparent, #ef4444, transparent);
            position: absolute;
            top: 0;
            animation: scan 3s linear infinite;
        }

        @keyframes scan {
            0% {
                top: 0%;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

    </style>
</head>
<body class="bg-cyber min-h-screen flex items-center justify-center overflow-hidden text-slate-300 antialiased">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-red-600/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600/5 rounded-full blur-[130px]"></div>
    </div>

    <div class="relative z-10 max-w-md w-full px-6">
        <div class="glass-panel relative rounded-2xl p-10 text-center overflow-hidden">
            <div class="scanner-line"></div>

            <div class="mb-6 relative inline-block">
                <div class="absolute inset-0 bg-red-500 blur-xl opacity-20 animate-pulse"></div>
                <div class="relative flex items-center justify-center w-16 h-16 rounded-xl bg-black border border-red-500/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-white mb-2 glow-text uppercase">
                Connection Severed
            </h1>

            <p class="text-sm text-slate-400 mb-8 leading-relaxed">
                The <span class="text-red-400 font-mono">HIVE_CORE</span> has flagged your credentials. Access to the portal is restricted while your account status is <span class="text-red-500 font-semibold italic">Suspended</span>.
            </p>

            <div class="space-y-4">
                <form method="POST" action="{{ route('custom.logout') }}">
                    @csrf
                    <button type="submit" class="w-full group relative flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-300 bg-transparent border border-white/10 rounded-lg hover:border-red-500/50 hover:bg-red-500/5">
                        <span class="relative flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="tracking-widest text-xs uppercase">Terminate Session</span>
                        </span>
                    </button>
                </form>

                <p class="text-[9px] font-mono text-slate-600 uppercase tracking-[0.2em]">
                    Internal Status: ACCESS_DENIED_S_12
                </p>
            </div>
        </div>
    </div>

</body>
</html>
