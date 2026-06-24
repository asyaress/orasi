(function () {
    'use strict';

    function initOrasiChatbot(root) {
        if (!root) {
            return;
        }

        var endpoint = root.getAttribute('data-endpoint');
        var csrf = root.getAttribute('data-csrf');
        var avatarUrl = root.getAttribute('data-avatar');
        var welcomeMessage = root.getAttribute('data-welcome') || '';
        var welcomeSuggestions = [];

        try {
            welcomeSuggestions = JSON.parse(root.getAttribute('data-suggestions') || '[]');
        } catch (error) {
            welcomeSuggestions = [];
        }

        var toggles = root.querySelectorAll('[data-chat-toggle]');
        var panel = root.querySelector('[data-chat-panel]');
        var closeBtn = root.querySelector('[data-chat-close]');
        var messages = root.querySelector('[data-chat-messages]');
        var suggestions = root.querySelector('[data-chat-suggestions]');
        var form = root.querySelector('[data-chat-form]');
        var input = root.querySelector('[data-chat-input]');
        var sendBtn = root.querySelector('[data-chat-send]');
        var typing = root.querySelector('[data-chat-typing]');
        var booted = false;

        function setOpen(isOpen) {
            root.classList.toggle('is-open', isOpen);

            if (isOpen) {
                root.classList.add('is-nudge-dismissed');
                bootChat();

                window.setTimeout(function () {
                    input.focus();
                }, 120);
            }
        }

        function bootChat() {
            if (booted) {
                return;
            }

            booted = true;
            appendBotMessage(welcomeMessage, 'greeting');
            renderSuggestions(welcomeSuggestions);
        }

        function appendMessage(html, role, extraClass) {
            var bubble = document.createElement('div');
            bubble.className = 'orasi-chatbot__message orasi-chatbot__message--' + role;

            if (extraClass) {
                bubble.classList.add(extraClass);
            }

            bubble.innerHTML = html;
            messages.insertBefore(bubble, typing);
            messages.scrollTop = messages.scrollHeight;
        }

        function appendUserMessage(text) {
            appendMessage(escapeHtml(text), 'user');
        }

        function appendBotMessage(html, type) {
            var extra = type === 'fallback' ? 'orasi-chatbot__message--fallback' : '';
            appendMessage(html, 'bot', extra);
        }

        function renderSuggestions(items) {
            suggestions.innerHTML = '';

            if (!items || !items.length) {
                return;
            }

            items.forEach(function (label) {
                var chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'orasi-chatbot__suggestion';
                chip.textContent = label;
                chip.addEventListener('click', function () {
                    input.value = label;
                    submitQuery(label);
                });
                suggestions.appendChild(chip);
            });
        }

        function setLoading(isLoading) {
            sendBtn.disabled = isLoading;
            input.disabled = isLoading;
            typing.hidden = !isLoading;
            typing.classList.toggle('is-visible', isLoading);

            if (isLoading) {
                messages.scrollTop = messages.scrollHeight;
            }
        }

        function submitQuery(text) {
            var query = (text || '').trim();

            if (!query) {
                return;
            }

            appendUserMessage(query);
            input.value = '';
            suggestions.innerHTML = '';
            setLoading(true);

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: query }),
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('network');
                    }

                    return response.json();
                })
                .then(function (payload) {
                    appendBotMessage(payload.message || 'Maaf, terjadi kesalahan.', payload.type || 'error');
                    renderSuggestions(payload.suggestions || []);
                })
                .catch(function () {
                    appendBotMessage(
                        'Maaf, koneksi gagal. Silakan coba lagi atau refresh halaman.',
                        'error'
                    );
                    renderSuggestions(welcomeSuggestions);
                })
                .finally(function () {
                    setLoading(false);
                    input.focus();
                });
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                setOpen(!root.classList.contains('is-open'));
            });
        });

        closeBtn.addEventListener('click', function () {
            setOpen(false);
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitQuery(input.value);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && root.classList.contains('is-open')) {
                setOpen(false);
            }
        });

        if (avatarUrl) {
            root.querySelectorAll('[data-chat-avatar]').forEach(function (img) {
                img.src = avatarUrl;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initOrasiChatbot(document.getElementById('orasi-chatbot'));
    });
})();
