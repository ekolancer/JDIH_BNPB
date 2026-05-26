<div
    x-data="{
        isOpen: false,
        isExpired: false,
        timeoutStr: '00:00',
        sessionLifetime: {{ config('session.lifetime') * 60 }},
        lastActiveTime: Date.now(),
        checkInterval: null,

        init() {
            this.startTimer();
            
            // Reset timer on user activity
            const resetEvents = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
            const resetHandler = () => {
                if (!this.isOpen && !this.isExpired) {
                    this.lastActiveTime = Date.now();
                }
            };
            
            resetEvents.forEach(event => {
                document.addEventListener(event, resetHandler, { passive: true });
            });

            // Also reset on Livewire request if modal is not open
            if (window.Livewire) {
                Livewire.hook('commit', () => {
                    if (!this.isOpen && !this.isExpired) {
                        this.lastActiveTime = Date.now();
                    }
                });
            }
        },

        startTimer() {
            this.checkInterval = setInterval(() => {
                const now = Date.now();
                const diffSeconds = Math.floor((now - this.lastActiveTime) / 1000);
                const remaining = this.sessionLifetime - diffSeconds;

                if (remaining <= 0) {
                    this.isExpired = true;
                    this.isOpen = true;
                    this.timeoutStr = '00:00';
                    clearInterval(this.checkInterval);
                } else if (remaining <= 60) {
                    // Show warning modal when 60 seconds left
                    this.isOpen = true;
                    const m = Math.floor(remaining / 60).toString().padStart(2, '0');
                    const s = (remaining % 60).toString().padStart(2, '0');
                    this.timeoutStr = `${m}:${s}`;
                } else {
                    this.isOpen = false;
                }
            }, 1000);
        },
        
        stayLoggedIn() {
            if (this.isExpired) return;
            
            // Make a dummy request to extend session
            fetch(window.location.href, { method: 'HEAD' }).then(() => {
                this.lastActiveTime = Date.now();
                this.isOpen = false;
                this.startTimer();
            }).catch(() => {
                // If it fails, they might be logged out already
                window.location.reload();
            });
        },
        
        reloadPage() {
            window.location.reload();
        }
    }"
    x-show="isOpen"
    style="display: none;"
    class="fixed inset-0 z-[9999] overflow-y-auto overflow-x-hidden flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 backdrop-blur-0"
    x-transition:enter-end="opacity-100 backdrop-blur-md"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 backdrop-blur-md"
    x-transition:leave-end="opacity-0 backdrop-blur-0"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm" x-show="isOpen" x-transition.opacity.duration.300ms></div>

    <!-- Modal Window -->
    <div 
        class="relative w-full max-w-md p-8 mx-auto fi-modal-window ring-1 ring-white/20 dark:ring-white/10"
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 delay-75"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
    >
        <div class="text-center" x-show="!isExpired">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-warning-500/20 text-warning-600 dark:text-warning-400 mb-5 shadow-[0_0_20px_rgba(234,179,8,0.3)] ring-1 ring-warning-500/30">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white font-heading">
                Session Expiring Soon
            </h3>
            
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                Your session is about to expire due to inactivity. You will be automatically logged out in:
            </p>

            <div class="mt-5 mb-8">
                <span class="inline-block px-4 py-2 text-4xl font-mono font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 rounded-xl ring-1 ring-primary-500/20 shadow-inner" x-text="timeoutStr"></span>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" @click="stayLoggedIn()" class="fi-btn fi-btn-color-primary flex-1 inline-flex items-center justify-center px-4 py-2.5 font-semibold text-sm rounded-xl bg-primary-600 hover:bg-primary-500 text-white shadow-lg shadow-primary-500/30 ring-1 ring-primary-500/50 transition-all">
                    Stay Logged In
                </button>
            </div>
        </div>
        
        <div class="text-center" x-show="isExpired" style="display: none;">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-danger-500/20 text-danger-600 dark:text-danger-400 mb-5 shadow-[0_0_20px_rgba(239,68,68,0.3)] ring-1 ring-danger-500/30">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            
            <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white font-heading">
                Session Expired
            </h3>
            
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 mb-8">
                Your session has expired due to inactivity. Please log in again to continue working.
            </p>
            
            <div class="flex justify-center">
                <button type="button" @click="reloadPage()" class="fi-btn fi-btn-color-primary w-full inline-flex items-center justify-center px-4 py-2.5 font-semibold text-sm rounded-xl bg-primary-600 hover:bg-primary-500 text-white shadow-lg shadow-primary-500/30 ring-1 ring-primary-500/50 transition-all">
                    Back to Login
                </button>
            </div>
        </div>
    </div>
</div>
