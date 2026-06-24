(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const panel = document.getElementById('a11y-panel');
        const trigger = document.getElementById('a11y-trigger');
        const closeBtn = document.getElementById('a11y-close');

        if (!panel || !trigger || !closeBtn) {
            return;
        }

        trigger.onclick = () => panel.classList.add('active');
        closeBtn.onclick = () => panel.classList.remove('active');

        let fontSize = 100;

        document.querySelectorAll('[data-action]').forEach(item => {
            item.addEventListener('click', () => {
                const action = item.dataset.action;

                if (action === 'increase') {
                    fontSize += 10;
                    document.documentElement.style.fontSize = fontSize + '%';
                }

                if (action === 'decrease') {
                    fontSize -= 10;
                    document.documentElement.style.fontSize = fontSize + '%';
                }

                if (action === 'reset') {
                    document.documentElement.className = '';
                    document.documentElement.style.fontSize = '100%';
                }
            });
        });

        document.querySelectorAll('[data-toggle]').forEach(item => {
            item.addEventListener('click', () => {
                const toggle = item.dataset.toggle;

                if (toggle === 'dark') {
                    document.documentElement.classList.toggle('theme-dark');
                }

                if (toggle === 'contrast') {
                    document.documentElement.classList.toggle('theme-contrast');
                }

                if (toggle === 'light') {
                    document.documentElement.classList.remove('theme-dark');
                }

                if (toggle === 'grayscale') {
                    document.documentElement.classList.toggle('grayscale');
                }

                if (toggle === 'readable') {
                    document.documentElement.classList.toggle('readable');
                }

                if (toggle === 'align') {
                    document.documentElement.classList.toggle('align');
                }
            });
        });

        document.querySelectorAll('.a11y-language button').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.a11y-language button')
                    .forEach(item => item.classList.remove('active'));

                this.classList.add('active');

                const lang = this.classList.contains('lang-en') ? 'en' : 'id';

                document.querySelectorAll('.a11y-menu li').forEach(item => {
                    const label = item.querySelector('.label');
                    if (label) {
                        label.textContent = item.dataset[lang];
                    }
                });

                const title = document.querySelector('.a11y-title');
                if (title) {
                    title.textContent = title.dataset[lang];
                }
            });
        });

        initVoiceMode();
    });

    function initVoiceMode() {
        if (!('speechSynthesis' in window)) {
            return;
        }

        const voiceItem = document.querySelector('[data-action="voice"]');
        if (!voiceItem) {
            return;
        }

        const voiceText = voiceItem.querySelector('.voice-text');

        let voiceEnabled = false;
        let voicesReady = false;
        let hoverTimeout = null;
        let currentElement = null;

        function setLoading() {
            voiceItem.classList.add('loading');
            voiceItem.classList.remove('ready', 'active');
            if (voiceText) {
                voiceText.textContent = 'Loading';
            }
        }

        function setReady() {
            voiceItem.classList.remove('loading');
            voiceItem.classList.add('ready');
            if (voiceText) {
                voiceText.textContent = 'Ready';
            }
            voicesReady = true;
        }

        function setActive() {
            voiceItem.classList.add('active');
            if (voiceText) {
                voiceText.textContent = 'On';
            }
        }

        function setInactive() {
            voiceItem.classList.remove('active');
            if (voiceText) {
                voiceText.textContent = 'Ready';
            }
        }

        function checkVoices() {
            const voices = speechSynthesis.getVoices();
            if (voices.length > 0) {
                setReady();
            } else {
                setLoading();
            }
        }

        function warmUp() {
            const dummy = new SpeechSynthesisUtterance('');
            speechSynthesis.speak(dummy);
            speechSynthesis.cancel();
        }

        function speakText(text) {
            speechSynthesis.cancel();
            speechSynthesis.resume();

            const utterance = new SpeechSynthesisUtterance(text);
            const voices = speechSynthesis.getVoices();
            const indoVoice = voices.find(voice => voice.lang && voice.lang.toLowerCase().includes('id'));

            if (indoVoice) {
                utterance.voice = indoVoice;
                utterance.lang = indoVoice.lang;
            } else {
                utterance.lang = 'id-ID';
            }

            utterance.rate = 1;
            utterance.pitch = 1;

            speechSynthesis.speak(utterance);
        }

        function stopSpeaking() {
            speechSynthesis.cancel();

            if (currentElement) {
                currentElement.classList.remove('voice-reading');
                currentElement = null;
            }
        }

        speechSynthesis.onvoiceschanged = checkVoices;
        setTimeout(checkVoices, 500);

        voiceItem.addEventListener('click', function () {
            if (!voicesReady) {
                return;
            }

            voiceEnabled = !voiceEnabled;

            speechSynthesis.cancel();
            speechSynthesis.resume();

            if (voiceEnabled) {
                setActive();
                warmUp();
            } else {
                setInactive();
                stopSpeaking();
            }
        });

        document.body.addEventListener('mouseover', function (event) {
            if (!voiceEnabled) {
                return;
            }

            const target = event.target;
            if (!target || target.closest('#a11y-panel')) {
                return;
            }

            const text = target.innerText?.trim();
            if (!text || text.length < 5) {
                return;
            }

            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(() => {
                currentElement = target;
                currentElement.classList.add('voice-reading');
                speakText(text);
            }, 400);
        });

        document.body.addEventListener('mouseout', function () {
            clearTimeout(hoverTimeout);
            stopSpeaking();
        });
    }
})();