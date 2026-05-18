{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure File Sharing System</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(to bottom right, #0f172a, #1e293b, #020617);
        }

        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .hero-glow {
            box-shadow: 0 0 80px rgba(56, 189, 248, 0.25);
        }
    </style>
</head>
<body class="text-white">

    {{-- NAVBAR --}}
    <header class="fixed top-0 left-0 w-full z-50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-400/20 p-2 rounded-xl">
                    <i data-lucide="shield-check" class="w-7 h-7 text-cyan-400"></i>
                </div>
                <h1 class="text-xl md:text-2xl font-bold tracking-wide">
                    Secure File Sharing
                </h1>
            </div>

            <nav class="hidden md:flex gap-8 text-sm font-medium">
                <a href="#about" class="hover:text-cyan-400 transition">About</a>
                <a href="#features" class="hover:text-cyan-400 transition">Features</a>
                <a href="#workflow" class="hover:text-cyan-400 transition">Workflow</a>
                <a href="#guide" class="hover:text-cyan-400 transition">Presentation Guide</a>
            </nav>

            <div class="flex gap-3">
                <a href="{{ route('login') }}"
                   class="px-5 py-2 rounded-xl border border-cyan-400 text-cyan-400 hover:bg-cyan-400 hover:text-slate-900 transition font-semibold">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 rounded-xl bg-emerald-400 text-slate-900 hover:bg-emerald-300 transition font-semibold">
                    Register
                </a>
            </div>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="min-h-screen flex items-center justify-center px-6 pt-32">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT CONTENT --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-cyan-500/10 border border-cyan-400/30 px-4 py-2 rounded-full mb-6">
                    <i data-lucide="lock" class="w-4 h-4 text-cyan-400"></i>
                    <span class="text-sm text-cyan-300">Confidentiality • Integrity • Authentication</span>
                </div>

                <h2 class="text-5xl md:text-7xl font-extrabold leading-tight">
                    Upload. Encrypt.
                    <span class="text-cyan-400">Protect.</span>
                </h2>

                <p class="mt-6 text-lg text-slate-300 leading-relaxed max-w-xl">
                    A secure web-based platform that protects files through encryption,
                    hashing, and user authentication — ensuring safe upload, storage,
                    and download of sensitive data.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 bg-cyan-400 text-slate-900 rounded-2xl font-bold hover:scale-105 transition">
                        Get Started
                    </a>

                    <a href="#about"
                       class="px-8 py-4 border border-white/20 rounded-2xl font-semibold hover:bg-white/10 transition">
                        Learn More
                    </a>
                </div>
            </div>

            {{-- RIGHT CARD --}}
            <div class="glass hero-glow rounded-3xl p-8">
                <h3 class="text-2xl font-bold mb-8">Core Security Goals</h3>

                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="bg-emerald-400/20 p-3 rounded-xl">
                            <i data-lucide="shield" class="text-emerald-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Confidentiality</h4>
                            <p class="text-slate-300 text-sm">Files are encrypted before storage to prevent unauthorized access.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="bg-yellow-400/20 p-3 rounded-xl">
                            <i data-lucide="badge-check" class="text-yellow-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Integrity</h4>
                            <p class="text-slate-300 text-sm">Hash verification ensures files remain unchanged.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="bg-cyan-400/20 p-3 rounded-xl">
                            <i data-lucide="key-round" class="text-cyan-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Authentication</h4>
                            <p class="text-slate-300 text-sm">Only verified users can access and manage files.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ABOUT --}}
    <section id="about" class="py-24 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-6">About the Project</h2>
            <p class="text-slate-300 max-w-4xl mx-auto text-lg leading-relaxed">
                This Secure File Sharing System is designed to demonstrate real-world cybersecurity
                implementation using encryption, secure authentication, and file integrity verification.
                It provides a practical solution for protecting digital files from unauthorized access,
                tampering, and security threats.
            </p>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="py-24 px-6 bg-white/5">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-14">System Features</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass rounded-3xl p-8 text-center">
                    <i data-lucide="user-check" class="mx-auto w-12 h-12 text-cyan-400 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Secure Login</h3>
                    <p class="text-slate-300">Password hashing and protected authentication system.</p>
                </div>

                <div class="glass rounded-3xl p-8 text-center">
                    <i data-lucide="upload-cloud" class="mx-auto w-12 h-12 text-emerald-400 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Encrypted Upload</h3>
                    <p class="text-slate-300">Files are encrypted using AES before storage.</p>
                </div>

                <div class="glass rounded-3xl p-8 text-center">
                    <i data-lucide="download" class="mx-auto w-12 h-12 text-yellow-400 mb-4"></i>
                    <h3 class="text-xl font-bold mb-3">Secure Download</h3>
                    <p class="text-slate-300">Authorized users can decrypt and verify files safely.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- WORKFLOW --}}
    <section id="workflow" class="py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-14">System Workflow</h2>

            <div class="grid md:grid-cols-6 gap-4 text-center">
                @foreach([
                    'Login',
                    'Upload',
                    'Encrypt',
                    'Store',
                    'Download',
                    'Decrypt'
                ] as $step)
                    <div class="glass rounded-2xl p-6">
                        <p class="font-bold">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- VIDEO GUIDE --}}
    <section id="guide" class="py-24 px-6 bg-white/5">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-14">Final Project Video Guide</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="glass rounded-3xl p-8">
                    <h3 class="text-2xl font-bold mb-4">Include in Your Video:</h3>
                    <ul class="space-y-3 text-slate-300">
                        <li>• System Overview</li>
                        <li>• Login, Upload, Download Demo</li>
                        <li>• Encryption & Decryption Code</li>
                        <li>• Security Explanation</li>
                        <li>• Equal Group Participation</li>
                    </ul>
                </div>

                <div class="glass rounded-3xl p-8">
                    <h3 class="text-2xl font-bold mb-4">Requirements:</h3>
                    <ul class="space-y-3 text-slate-300">
                        <li>• 10–20 Minutes Duration</li>
                        <li>• MP4 / Google Drive / YouTube</li>
                        <li>• Visible and Readable Code</li>
                        <li>• Explain AES + Hashing</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-10 text-center border-t border-white/10">
        <p class="text-slate-400">
            Security is not just a feature — it is the foundation.
        </p>
    </footer>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>