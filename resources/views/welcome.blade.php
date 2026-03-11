<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Do'kon — Buyurtma Agenti</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0f0e0c;
            --surface: #1a1915;
            --surface2: #232219;
            --border: #2e2c26;
            --gold: #c9a84c;
            --gold-dim: #7a5f28;
            --text: #e8e2d5;
            --text-muted: #7a7468;
            --user-bg: #c9a84c;
            --user-text: #0f0e0c;
            --bot-bg: #232219;
            --radius: 16px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 28px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            flex-shrink: 0;
            position: relative;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: var(--gold);
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .header-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .3px;
        }

        .header-info p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4caf7d;
            border-radius: 50%;
            margin-left: auto;
            box-shadow: 0 0 8px #4caf7d88;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .4
            }
        }

        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            scroll-behavior: smooth;
        }

        #messages::-webkit-scrollbar {
            width: 4px;
        }

        #messages::-webkit-scrollbar-track {
            background: transparent;
        }

        #messages::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        .welcome-msg {
            text-align: center;
            padding: 40px 20px 10px;
        }

        .welcome-msg .icon {
            font-size: 48px;
            display: block;
            margin-bottom: 12px;
        }

        .welcome-msg h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .welcome-msg p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
        }

        .chip {
            padding: 8px 16px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 20px;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .chip:hover {
            border-color: var(--gold-dim);
            color: var(--gold);
            background: #1f1d14;
        }

        .msg {
            display: flex;
            gap: 10px;
            max-width: 82%;
            animation: msgIn .25s ease;
        }

        @keyframes msgIn {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .msg.user {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .msg.bot {
            align-self: flex-start;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 15px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .msg.user .avatar {
            background: var(--gold);
            color: var(--user-text);
        }

        .msg.bot .avatar {
            background: var(--surface2);
            border: 1px solid var(--border);
        }

        .bubble {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 14.5px;
            line-height: 1.65;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .msg.user .bubble {
            background: var(--gold);
            color: var(--user-text);
            border-bottom-right-radius: 4px;
            font-weight: 500;
        }

        .msg.bot .bubble {
            background: var(--bot-bg);
            border: 1px solid var(--border);
            border-bottom-left-radius: 4px;
            color: var(--text);
        }

        /* Tool indicator */
        .tool-indicator {
            align-self: flex-start;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            background: #1a2218;
            border: 1px solid #2d4a35;
            border-radius: 20px;
            font-size: 12px;
            color: #5a9a6a;
            font-family: 'DM Sans', monospace;
            animation: msgIn .25s ease;
        }

        .tool-spin {
            width: 12px;
            height: 12px;
            border: 1.5px solid #2d4a35;
            border-top-color: #5a9a6a;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Typing dots */
        .typing .bubble {
            padding: 14px 18px;
        }

        .dots {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .dots span {
            width: 6px;
            height: 6px;
            background: var(--gold-dim);
            border-radius: 50%;
            animation: bounce .9s infinite;
        }

        .dots span:nth-child(2) {
            animation-delay: .15s;
        }

        .dots span:nth-child(3) {
            animation-delay: .30s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: translateY(0)
            }

            40% {
                transform: translateY(-6px)
            }
        }

        /* Streaming cursor */
        .cursor::after {
            content: '▋';
            color: var(--gold);
            animation: blink .7s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0
            }
        }

        .input-wrap {
            padding: 16px 20px 20px;
            border-top: 1px solid var(--border);
            background: var(--surface);
            flex-shrink: 0;
        }

        .input-row {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 8px 8px 8px 16px;
            transition: border-color .2s;
        }

        .input-row:focus-within {
            border-color: var(--gold-dim);
        }

        #userInput {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            resize: none;
            max-height: 120px;
            line-height: 1.5;
            padding: 4px 0;
        }

        #userInput::placeholder {
            color: var(--text-muted);
        }

        #userInput::-webkit-scrollbar {
            width: 3px;
        }

        #userInput::-webkit-scrollbar-thumb {
            background: var(--border);
        }

        #sendBtn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: none;
            background: var(--gold);
            color: var(--bg);
            cursor: pointer;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            transition: all .2s;
            font-size: 16px;
        }

        #sendBtn:hover:not(:disabled) {
            background: #d4b05a;
            transform: scale(1.05);
        }

        #sendBtn:disabled {
            background: var(--border);
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
        }

        .input-hint {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 10px;
        }

        .new-chat-btn {
            position: absolute;
            top: 18px;
            right: 72px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s;
        }

        .new-chat-btn:hover {
            border-color: var(--gold-dim);
            color: var(--gold);
        }

        @media (max-width: 600px) {
            .msg {
                max-width: 94%;
            }

            header {
                padding: 14px 16px;
            }

            #messages {
                padding: 16px 12px;
            }

            .input-wrap {
                padding: 12px 12px 16px;
            }

            .welcome-msg {
                padding: 20px 10px 0;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">🛒</div>
        <div class="header-info">
            <h1>Do'kon Agenti</h1>
            <p>Buyurtma yordamchisi</p>
        </div>
        <button class="new-chat-btn" onclick="newChat()">+ Yangi</button>
        <div class="status-dot"></div>
    </header>

    <div id="messages">
        <div class="welcome-msg" id="welcomeBlock">
            <span class="icon">🌿</span>
            <h2>Assalomu alaykum!</h2>
            <p>Men sizga mahsulot tanlash va buyurtma berishda<br>yordam beraman. Nima kerak?</p>
            <div class="suggestions">
                <button class="chip" onclick="quickSend('1 kg olma kerak')">🍎 1 kg olma</button>
                <button class="chip" onclick="quickSend('2 kg nok va 1 kg uzum')">🍐 Nok va uzum</button>
                <button class="chip" onclick="quickSend('Mevalar ro\'yxatini ko\'rsating')">📋 Mevalar ro'yxati</button>
                <button class="chip" onclick="quickSend('Sabzavotlar bor?')">🥕 Sabzavotlar</button>
            </div>
        </div>
    </div>

    <div class="input-wrap">
        <div class="input-row">
            <textarea id="userInput" placeholder="Xabar yozing... (Enter — yuborish)" rows="1" autofocus></textarea>
            <button id="sendBtn" onclick="sendMessage()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </div>
        <p class="input-hint">Enter — yuborish &nbsp;·&nbsp; Shift+Enter — yangi qator</p>
    </div>

    <script>
        const START_URL = '/api/orders/chat/start';
        const STREAM_URL = '/api/orders/chat/stream';
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        const TOOL_LABELS = {
            search_products: '🔍 Mahsulotlar qidirilmoqda...',
            create_order: '📦 Buyurtma yaratilmoqda...',
        };

        let conversationId = null;
        let isLoading = false;

        const messagesEl = document.getElementById('messages');
        const inputEl = document.getElementById('userInput');
        const sendBtn = document.getElementById('sendBtn');

        /* ── textarea auto-resize ── */
        inputEl.addEventListener('input', () => {
            inputEl.style.height = 'auto';
            inputEl.style.height = Math.min(inputEl.scrollHeight, 120) + 'px';
        });

        inputEl.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });

        function quickSend(text) { inputEl.value = text; sendMessage(); }

        function newChat() {
            conversationId = null;
            messagesEl.innerHTML = `
                <div class="welcome-msg" id="welcomeBlock">
                    <span class="icon">🌿</span>
                    <h2>Yangi suhbat</h2>
                    <p>Nima buyurtma berishni xohlaysiz?</p>
                    <div class="suggestions">
                        <button class="chip" onclick="quickSend('1 kg olma kerak')">🍎 1 kg olma</button>
                        <button class="chip" onclick="quickSend('2 kg nok va 1 kg uzum')">🍐 Nok va uzum</button>
                        <button class="chip" onclick="quickSend('Mevalar ro\\'yxatini ko\\'rsating')">📋 Mevalar ro'yxati</button>
                        <button class="chip" onclick="quickSend('Sabzavotlar bor?')">🥕 Sabzavotlar</button>
                    </div>
                </div>`;
            inputEl.focus();
        }

        /* ── DOM helpers ── */
        function scrollBottom() { messagesEl.scrollTop = messagesEl.scrollHeight; }

        function setLoading(v) {
            isLoading = v;
            sendBtn.disabled = v;
            inputEl.disabled = v;
        }

        function appendMessage(role, text = '') {
            document.getElementById('welcomeBlock')?.remove();
            const wrap = document.createElement('div');
            wrap.className = `msg ${role}`;
            const avatar = document.createElement('div');
            avatar.className = 'avatar';
            avatar.textContent = role === 'user' ? '👤' : '🛒';
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.textContent = text;
            wrap.appendChild(avatar);
            wrap.appendChild(bubble);
            messagesEl.appendChild(wrap);
            scrollBottom();
            return bubble;
        }

        function showTyping() {
            const wrap = document.createElement('div');
            wrap.className = 'msg bot typing'; wrap.id = 'thinking';
            const avatar = document.createElement('div');
            avatar.className = 'avatar'; avatar.textContent = '🛒';
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.innerHTML = '<div class="dots"><span></span><span></span><span></span></div>';
            wrap.appendChild(avatar); wrap.appendChild(bubble);
            messagesEl.appendChild(wrap); scrollBottom();
        }

        function setToolLabel(label) {
            document.getElementById('thinking')?.remove();
            let el = document.getElementById('toolIndicator');
            if (!el) {
                el = document.createElement('div');
                el.className = 'tool-indicator'; el.id = 'toolIndicator';
                messagesEl.appendChild(el);
            }
            el.innerHTML = `<div class="tool-spin"></div> ${label}`;
            scrollBottom();
        }

        function clearIndicators() {
            document.getElementById('thinking')?.remove();
            document.getElementById('toolIndicator')?.remove();
        }

        function createStreamBubble() {
            clearIndicators();
            const wrap = document.createElement('div');
            wrap.className = 'msg bot';
            const avatar = document.createElement('div');
            avatar.className = 'avatar'; avatar.textContent = '🛒';
            const bubble = document.createElement('div');
            bubble.className = 'bubble cursor';
            wrap.appendChild(avatar); wrap.appendChild(bubble);
            messagesEl.appendChild(wrap); scrollBottom();
            return bubble;
        }

        /* ── Typewriter for non-stream responses ── */
        function typeText(el, text) {
            return new Promise(resolve => {
                let i = 0;
                const speed = Math.max(8, Math.min(20, 1200 / text.length));
                function tick() {
                    if (i < text.length) {
                        el.textContent = text.slice(0, ++i);
                        scrollBottom();
                        setTimeout(tick, speed);
                    } else { resolve(); }
                }
                tick();
            });
        }

        /* ── Main send ── */
        async function sendMessage() {
            const text = inputEl.value.trim();
            if (!text || isLoading) return;

            inputEl.value = '';
            inputEl.style.height = 'auto';
            appendMessage('user', text);
            setLoading(true);
            showTyping();

            /* ── 1-xabar: oddiy POST → conversation_id olamiz ── */
            if (!conversationId) {
                try {
                    const res = await fetch(START_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ message: text }),
                    });

                    clearIndicators();

                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        appendMessage('bot', '❌ Xatolik: ' + (err.message || res.statusText));
                        return;
                    }

                    const data = await res.json();
                    conversationId = data.conversation_id;

                    // typewriter effekt
                    const bubble = appendMessage('bot');
                    bubble.classList.add('cursor');
                    await typeText(bubble, data.message ?? '');
                    bubble.classList.remove('cursor');

                } catch (err) {
                    clearIndicators();
                    appendMessage('bot', '❌ Server bilan bog\'lanishda xatolik.');
                    console.error(err);
                } finally {
                    setLoading(false);
                    inputEl.focus();
                }
                return;
            }

            /* ── Keyingi xabarlar: SSE stream ── */
            try {
                const res = await fetch(STREAM_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({
                        conversation_id: conversationId,
                        message: text,
                    }),
                });

                if (!res.ok) {
                    clearIndicators();
                    const err = await res.json().catch(() => ({}));
                    appendMessage('bot', '❌ Xatolik: ' + (err.message || res.statusText));
                    return;
                }

                const reader = res.body.getReader();
                const decoder = new TextDecoder();
                let buf = '';
                let bubble = null;
                let full = '';

                outer: while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    buf += decoder.decode(value, { stream: true });
                    const lines = buf.split('\n');
                    buf = lines.pop();

                    for (const line of lines) {
                        if (!line.startsWith('data:')) continue;
                        const raw = line.slice(5).trim();
                        if (raw === '[DONE]') break outer;

                        let evt;
                        try { evt = JSON.parse(raw); } catch { continue; }

                        // conversation_id
                        if (evt.type === 'conversation_id') { conversationId = evt.content; continue; }
                        if (evt.conversation_id) { conversationId = evt.conversation_id; continue; }

                        // tool call → spinner
                        if (evt.type === 'tool_call') {
                            bubble = null;
                            setToolLabel(TOOL_LABELS[evt.name] ?? '⚙️ ' + evt.name);
                            continue;
                        }

                        // tool result → spinner o'chadi
                        if (evt.type === 'tool_result') {
                            document.getElementById('toolIndicator')?.remove();
                            continue;
                        }

                        // stream tugadi
                        if (evt.type === 'stream_end' || evt.type === 'text_end') {
                            if (bubble) bubble.classList.remove('cursor');
                            continue;
                        }

                        // ✅ Laravel AI SDK: type="text_delta", field="delta"
                        if (evt.type === 'text_delta' && evt.delta) {
                            if (!bubble) { bubble = createStreamBubble(); full = ''; }
                            full += evt.delta;
                            bubble.textContent = full;
                            scrollBottom();
                        }
                    }
                }

                if (bubble) bubble.classList.remove('cursor');
                else clearIndicators();

            } catch (err) {
                clearIndicators();
                appendMessage('bot', '❌ Ulanishda xatolik: ' + err.message);
                console.error(err);
            } finally {
                setLoading(false);
                inputEl.focus();
            }
        }
    </script>
</body>

</html>