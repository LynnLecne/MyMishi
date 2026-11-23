@auth
    {{-- Formulário oculto para logout seguro via POST --}}
    <form id="inactivity-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    {{-- Modal de Aviso de Logout por Inatividade --}}
    <div class="modal fade" id="inactivityWarningModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                <div class="modal-header bg-pink-subtle border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-pink mb-0">⏳ Aviso de Inatividade</h5>
                </div>
                <div class="modal-body p-4 bg-white text-center">
                    <p class="fw-semibold fs-6 mb-2">
                        Vais fazer logout dentro de <span id="inactivity-countdown" class="text-pink fw-bold">30</span> segundos devido a inatividade.
                    </p>
                    <p class="text-muted small mb-0">
                        Clica no botão abaixo se quiseres continuar com a sessão iniciada.
                    </p>
                </div>
                <div class="modal-footer border-0 bg-white justify-content-center pb-4">
                    <button type="button" class="btn btn-pink rounded-pill px-4" onclick="resetInactivityTimer()">
                        Continuar Sessão
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const TIMEOUT_MS = 5 * 60 * 1000; // 5 minutos até ao logout  
            const WARNING_MS = 30 * 1000;     // Mostra o aviso 30 segundos antes (aos 4m30s)

            let logoutTimer;
            let warningTimer;
            let countdownInterval;
            let isWarningVisible = false;

            const modalEl = document.getElementById('inactivityWarningModal');
            const countdownEl = document.getElementById('inactivity-countdown');
            const logoutForm = document.getElementById('inactivity-logout-form');

            function showWarning() {
                isWarningVisible = true;
                let secondsLeft = WARNING_MS / 1000;
                countdownEl.textContent = secondsLeft;

                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                document.body.classList.add('modal-open');

                countdownInterval = setInterval(() => {
                    secondsLeft--;
                    if (secondsLeft >= 0) {
                        countdownEl.textContent = secondsLeft;
                    }
                }, 1000);
            }

            function hideWarning() {
                if (!isWarningVisible) return;
                isWarningVisible = false;
                clearInterval(countdownInterval);
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
            }

            function performLogout() {
                clearInterval(countdownInterval);
                logoutForm.submit();
            }

            window.resetInactivityTimer = function () {
                 if (isWarningVisible) {
        fetch('{{ url()->current() }}', { method: 'HEAD', credentials: 'same-origin' });
    }
                clearTimeout(warningTimer);
                clearTimeout(logoutTimer);
                hideWarning();

                warningTimer = setTimeout(showWarning, TIMEOUT_MS - WARNING_MS);
                logoutTimer = setTimeout(performLogout, TIMEOUT_MS);
            };

            // Reinicia o contador sempre que há atividade (antes de o modal aparecer)
            ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, function () {
                    if (!isWarningVisible) {
                        window.resetInactivityTimer();
                    }
                }, { passive: true });
            });

            window.resetInactivityTimer();
        })();
    </script>
@endauth
