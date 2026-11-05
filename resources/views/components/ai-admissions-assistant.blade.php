@once
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* ==========================================
       ABBYERO AVIATION - ADMISSIONS ASSISTANT
    ========================================== */

    #abbyero-ai-launcher,
    #abbyero-ai-panel,
    #abbyero-ai-panel * {
        box-sizing: border-box;
    }

    :root {
        --abbyero-navy: #243d74;
        --abbyero-navy-light: #103d60;
        --abbyero-blue: #52718c;
        --abbyero-bg: #f4f7fa;
        --abbyero-border: #e4eaf0;
        --abbyero-text: #263746;
        --abbyero-muted: #718096;
    }

    /* Floating launcher */

    #abbyero-ai-launcher {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 3000;

        width: 62px;
        height: 62px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 0;
        border-radius: 50%;

        background: var(--abbyero-navy);
        color: #fff;

        font-size: 24px;

        box-shadow: 0 5px 20px rgba(7, 39, 67, .28);

        cursor: pointer;
        transition: transform .25s ease, background .25s ease,
                    box-shadow .25s ease;
    }

    #abbyero-ai-launcher:hover {
        background: var(--abbyero-navy-light);
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(7, 39, 67, .35);
    }

    #abbyero-ai-launcher:focus-visible,
    #abbyero-ai-close:focus-visible,
    #abbyero-ai-send:focus-visible {
        outline: 3px solid #8eb9dc;
        outline-offset: 3px;
    }

    /* Chat panel */

    #abbyero-ai-panel {
        display: none;

        position: fixed;
        right: 24px;
        bottom: 100px;
        z-index: 3001;

        width: 390px;
        max-width: calc(100vw - 32px);

        height: 560px;
        max-height: calc(100vh - 130px);

        background: #fff;

        border: 1px solid rgba(7, 39, 67, .08);
        border-radius: 18px;

        box-shadow: 0 12px 45px rgba(7, 39, 67, .20);

        overflow: hidden;
        flex-direction: column;

        font-family: Arial, Helvetica, sans-serif;
    }

    #abbyero-ai-panel.open {
        display: flex;
        animation: abbyeroChatOpen .22s ease-out;
    }

    @keyframes abbyeroChatOpen {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header */

    .abbyero-ai-header {
        background: linear-gradient(
            135deg,
            var(--abbyero-navy),
            var(--abbyero-navy-light)
        );

        color: #fff;
        padding: 17px 18px;

        display: flex;
        justify-content: space-between;
        align-items: center;

        flex-shrink: 0;
    }

    .abbyero-ai-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .abbyero-ai-header-icon {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 12px;
        background: rgba(255, 255, 255, .13);

        font-size: 19px;
    }

    .abbyero-ai-header strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .abbyero-ai-header small {
        display: block;
        color: rgba(255, 255, 255, .78);
        font-size: 11px;
    }

    .abbyero-ai-header-status {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        font-size: 10px;
        color: #d5e9f7;
    }

    .abbyero-ai-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #65d69a;
    }

    #abbyero-ai-close {
        width: 34px;
        height: 34px;

        border: 0;
        border-radius: 8px;

        background: transparent;
        color: #fff;

        font-size: 25px;
        line-height: 1;

        cursor: pointer;
        transition: background .2s ease;
    }

    #abbyero-ai-close:hover {
        background: rgba(255, 255, 255, .15);
    }

    /* Messages area */

    #abbyero-ai-messages {
        flex: 1;
        min-height: 0;

        overflow-y: auto;
        overscroll-behavior: contain;

        padding: 17px;

        background: var(--abbyero-bg);
        scroll-behavior: smooth;
    }

    #abbyero-ai-messages::-webkit-scrollbar {
        width: 5px;
    }

    #abbyero-ai-messages::-webkit-scrollbar-thumb {
        background: #cbd5df;
        border-radius: 10px;
    }

    .abbyero-ai-message {
        max-width: 88%;
        width: fit-content;

        padding: 12px 14px;

        border-radius: 13px;
        margin-bottom: 13px;

        font-size: 13px;
        line-height: 1.65;

        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .abbyero-ai-message.bot {
        background: #fff;
        color: var(--abbyero-text);

        border: 1px solid var(--abbyero-border);
        border-top-left-radius: 4px;

        margin-right: auto;
    }

    .abbyero-ai-message.user {
        background: var(--abbyero-navy);
        color: #fff;

        border-top-right-radius: 4px;

        margin-left: auto;
    }
/* Welcome screen */

.ai-welcome {
    width: 100%;
    padding: 5px 1px 12px;
}

.ai-welcome-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.ai-welcome-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #072743;
    color: #fff;

    border-radius: 13px;
    font-size: 20px;
}

.ai-welcome-heading h5 {
    color: #072743;
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 5px;
}

.ai-welcome-heading span {
    display: block;
    color: #718096;
    font-size: 11px;
}

.ai-welcome-description {
    color: #596b7b;
    font-size: 12px;
    line-height: 1.7;
    margin-bottom: 15px;
}

.ai-welcome-divider {
    height: 1px;
    background: #e4eaf0;
    margin: 14px 0;
}

.ai-quick-heading {
    color: #072743;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 11px;
}

.ai-quick-questions {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.ai-quick-questions button {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 11px;

    padding: 11px 10px;

    background: #fff;
    border: 1px solid #e4eaf0;
    border-radius: 10px;

    text-align: left;
    cursor: pointer;

    transition: all .2s ease;
}

.ai-quick-questions button:hover {
    background: #f0f5f9;
    border-color: #a8bac9;
    transform: translateY(-1px);
}

.ai-question-icon {
    width: 35px;
    height: 35px;
    min-width: 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #edf3f8;
    color: #072743;

    border-radius: 9px;
    font-size: 14px;
}

.ai-question-text {
    flex: 1;
    min-width: 0;
}

.ai-question-text strong {
    display: block;
    color: #072743;
    font-size: 11.5px;
    font-weight: 700;
    margin-bottom: 3px;
}

.ai-question-text small {
    display: block;
    color: #7b8996;
    font-size: 10.5px;
}

.ai-question-arrow {
    color: #9aa8b5;
    font-size: 10px;
}

.ai-welcome-footer {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-top: 15px;
    padding: 10px;

    background: #edf3f8;
    border-radius: 8px;

    color: #526a80;
    font-size: 10.5px;
}

.ai-welcome-footer i {
    color: #072743;
}

    /* Footer and input */

    .abbyero-ai-footer {
        padding: 13px 15px 11px;

        background: #fff;
        border-top: 1px solid var(--abbyero-border);

        flex-shrink: 0;
    }

    .abbyero-ai-input-row {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    #abbyero-ai-input {
        flex: 1;
        min-width: 0;
        height: 43px;

        padding: 0 13px;

        border: 1px solid #d6dee6;
        border-radius: 10px;

        background: #f9fbfc;
        color: var(--abbyero-text);

        font-size: 13px;

        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    #abbyero-ai-input:focus {
        border-color: var(--abbyero-blue);
        box-shadow: 0 0 0 3px rgba(82, 113, 140, .10);
        background: #fff;
    }

    #abbyero-ai-input::placeholder {
        color: #98a5b2;
    }

    #abbyero-ai-send {
        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 0;
        border-radius: 10px;

        background: var(--abbyero-navy);
        color: #fff;

        font-size: 15px;
        cursor: pointer;

        transition: background .2s ease, transform .2s ease;
    }

    #abbyero-ai-send:hover:not(:disabled) {
        background: var(--abbyero-navy-light);
        transform: translateY(-1px);
    }

    #abbyero-ai-send:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .abbyero-ai-note {
        display: block;
        margin-top: 10px;

        color: var(--abbyero-muted);
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
    }

    .abbyero-ai-note a {
        color: var(--abbyero-navy);
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .abbyero-ai-note a:hover {
        text-decoration: underline;
    }

    /* Mobile responsiveness */

    @media (max-width: 480px) {
        #abbyero-ai-panel {
            right: 10px;
            bottom: 85px;

            width: calc(100vw - 20px);
            height: min(560px, calc(100dvh - 105px));
            max-height: calc(100dvh - 105px);

            border-radius: 15px;
        }

        #abbyero-ai-launcher {
            right: 16px;
            bottom: 16px;

            width: 56px;
            height: 56px;
            font-size: 22px;
        }

        .abbyero-ai-header {
            padding: 14px;
        }

        #abbyero-ai-messages {
            padding: 13px;
        }

        .abbyero-ai-message {
            max-width: 94%;
        }

        .abbyero-ai-footer {
            padding: 11px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #abbyero-ai-panel.open {
            animation: none;
        }

        #abbyero-ai-launcher,
        .ai-quick-questions button {
            transition: none;
        }
    }
</style>

<!-- Floating launcher -->

<button
    id="abbyero-ai-launcher"
    type="button"
    aria-label="Open admissions assistant"
    aria-expanded="false"
    aria-controls="abbyero-ai-panel"
    title="Ask Abbyero Admissions"
>
    <i class="fas fa-comments" aria-hidden="true"></i>
</button>

<!-- Admissions assistant panel -->

<section
    id="abbyero-ai-panel"
    aria-label="Abbyero Admissions Assistant"
    data-endpoint="{{ route('ai.admissions.chat') }}"
    data-token="{{ csrf_token() }}"
>
    <!-- Header -->

    <header class="abbyero-ai-header">

        <div class="abbyero-ai-header-info">

            <div class="abbyero-ai-header-icon">
                <i class="fas fa-plane-departure" aria-hidden="true"></i>
            </div>

            <div>
                <strong>Abbyero Admissions Assistant</strong>

                <small>Flight training information and enquiries</small>

                <div class="abbyero-ai-header-status">
                    <span class="abbyero-ai-status-dot"></span>
                    Virtual assistant
                </div>
            </div>

        </div>

        <button
            id="abbyero-ai-close"
            type="button"
            aria-label="Close chat"
        >
            &times;
        </button>

    </header>

    <!-- Chat messages -->

<div id="abbyero-ai-messages" aria-live="polite" aria-relevant="additions">

    <div class="ai-welcome">

        <div class="ai-welcome-heading">
            <div class="ai-welcome-icon">
                <i class="fas fa-plane-departure"></i>
            </div>

            <div>
                <h5>Welcome to Abbyero Aviation</h5>
                <span>Your flight training journey starts here.</span>
            </div>
        </div>

        <p class="ai-welcome-description">
            I'm your virtual admissions assistant.
            Ask me about our flight training programs, fees
            and how to apply.
        </p>

        <div class="ai-welcome-divider"></div>

        <div class="ai-quick-heading">
            How can I help you today?
        </div>

        <div class="ai-quick-questions">

            <button type="button"
                data-message="What flight training programs do you offer?">
                <span class="ai-question-icon">
                    <i class="fas fa-graduation-cap"></i>
                </span>
                <span class="ai-question-text">
                    <strong>Training Programs</strong>
                    <small>Explore available courses</small>
                </span>
                <i class="fas fa-chevron-right ai-question-arrow"></i>
            </button>

            <button type="button"
                data-message="How much do your flight training programs cost?">
                <span class="ai-question-icon">
                    <i class="fas fa-dollar-sign"></i>
                </span>
                <span class="ai-question-text">
                    <strong>Program Fees</strong>
                    <small>Explore training costs</small>
                </span>
                <i class="fas fa-chevron-right ai-question-arrow"></i>
            </button>

            <button type="button"
                data-message="What are the admission requirements?">
                <span class="ai-question-icon">
                    <i class="fas fa-clipboard-check"></i>
                </span>
                <span class="ai-question-text">
                    <strong>Admission Requirements</strong>
                    <small>Learn about eligibility</small>
                </span>
                <i class="fas fa-chevron-right ai-question-arrow"></i>
            </button>

            <button type="button"
                data-message="How do I apply for flight training?">
                <span class="ai-question-icon">
                    <i class="fas fa-file-alt"></i>
                </span>
                <span class="ai-question-text">
                    <strong>Application Process</strong>
                    <small>Find out how to apply</small>
                </span>
                <i class="fas fa-chevron-right ai-question-arrow"></i>
            </button>

        </div>

        <div class="ai-welcome-footer">
            <i class="fas fa-info-circle"></i>
            Select a topic or type your question below.
        </div>

    </div>

</div>
    <!-- Footer -->

    <footer class="abbyero-ai-footer">

        <form id="abbyero-ai-form">

            <div class="abbyero-ai-input-row">

                <input
                    id="abbyero-ai-input"
                    type="text"
                    maxlength="2000"
                    placeholder="Ask an admissions question..."
                    autocomplete="off"
                    aria-label="Type your admissions question"
                    required
                >

                <button
                    id="abbyero-ai-send"
                    type="submit"
                    aria-label="Send message"
                >
                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                </button>

            </div>

        </form>

        <small class="abbyero-ai-note">
            AI-generated information. Final admission and enrollment
            details must be confirmed by Abbyero Aviation staff.

            <a href="{{ url('/flight-school#apply') }}">
                Apply / Enquire
            </a>
        </small>

    </footer>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const launcher = document.getElementById('abbyero-ai-launcher');
    const panel = document.getElementById('abbyero-ai-panel');
    const closeButton = document.getElementById('abbyero-ai-close');
    const form = document.getElementById('abbyero-ai-form');
    const input = document.getElementById('abbyero-ai-input');
    const sendButton = document.getElementById('abbyero-ai-send');
    const messages = document.getElementById('abbyero-ai-messages');

    if (!launcher || !panel || !closeButton || !form ||
        !input || !sendButton || !messages) {
        return;
    }

    const endpoint = panel.dataset.endpoint;
    const token = panel.dataset.token;

    let history = [];

    /* Open and close chat */

    function toggleChat(open) {

        panel.classList.toggle('open', open);

        launcher.setAttribute('aria-expanded', String(open));

        if (open) {
            input.focus();
        } else {
            launcher.focus();
        }
    }

    launcher.addEventListener('click', function () {
        toggleChat(!panel.classList.contains('open'));
    });

    closeButton.addEventListener('click', function () {
        toggleChat(false);
    });

    /* Close using Escape */

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            panel.classList.contains('open')
        ) {
            toggleChat(false);
        }

    });

    /* Add chat message */

    function addMessage(text, sender) {

        const message = document.createElement('div');

        message.className = 'abbyero-ai-message ' + sender;

        // Use textContent to prevent HTML injection.
        message.textContent = text;

        messages.appendChild(message);

        messages.scrollTop = messages.scrollHeight;

        return message;
    }

    /* Suggested question buttons */

    messages.addEventListener('click', function (event) {

        const button = event.target.closest('.ai-quick-questions button');

        if (!button || sendButton.disabled) {
            return;
        }

        const suggestedMessage = button.dataset.message;

        if (!suggestedMessage) {
            return;
        }

        input.value = suggestedMessage;

        form.requestSubmit();

    });

    /* Send chat message */

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        const message = input.value.trim();

        if (!message || sendButton.disabled) {
            return;
        }

        const previousHistory = [...history];

        addMessage(message, 'user');

        input.value = '';

        history.push({
            role: 'user',
            content: message
        });

        sendButton.disabled = true;
        input.disabled = true;

        const loadingMessage = addMessage('Thinking...', 'bot');

        try {

            const response = await fetch(endpoint, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },

                body: JSON.stringify({

                    message: message,

                    history: previousHistory.slice(-10)

                })

            });

            const data = await response.json();

            if (!response.ok || !data.success) {

                throw new Error(
                    data.message || 'Unable to get a response.'
                );

            }

            loadingMessage.remove();

            addMessage(data.reply, 'bot');

            history.push({
                role: 'assistant',
                content: data.reply
            });

            history = history.slice(-10);

        } catch (error) {

            loadingMessage.remove();

            addMessage(
                error.message ||
                'The assistant is temporarily unavailable. Please try again.',
                'bot'
            );

            // Restore history when a request fails.
            history = previousHistory;

        } finally {

            sendButton.disabled = false;
            input.disabled = false;
            input.focus();

        }

    });

});
</script>

@endonce
