<?php
// c:\xampp\htdocs\pagamuma\mothers\pages\chatbot.php
?>
<style>
/* ===== PAG-AMUMA Chat UI ===== */
#chatContainer {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 220px);
    min-height: 500px;
}

#chatWindow {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    background: #f8f9fc;
    scroll-behavior: smooth;
}

/* Scrollbar styling */
#chatWindow::-webkit-scrollbar { width: 5px; }
#chatWindow::-webkit-scrollbar-track { background: transparent; }
#chatWindow::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }

/* Message bubbles */
.msg-row { display: flex; margin-bottom: 1.2rem; align-items: flex-end; gap: 10px; }
.msg-row.user-row { flex-direction: row-reverse; }

.msg-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.85rem;
}
.bot-avatar  { background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; }
.user-avatar { background: linear-gradient(135deg, #ec4899, #f43f5e); color: #fff; }

.msg-bubble {
    max-width: 72%;
    padding: 0.85rem 1.1rem;
    border-radius: 18px;
    font-size: 0.92rem;
    line-height: 1.6;
    word-break: break-word;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.bot-bubble {
    background: #fff;
    color: #1e1e2e;
    border-bottom-left-radius: 4px;
}
.user-bubble {
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.msg-time {
    font-size: 0.68rem;
    margin-top: 0.35rem;
    opacity: 0.55;
    display: block;
}
.user-row .msg-time { text-align: right; }

/* Typing indicator */
.typing-indicator { display: flex; align-items: center; gap: 5px; padding: 4px 2px; }
.typing-indicator span {
    width: 8px; height: 8px;
    background: #a855f7;
    border-radius: 50%;
    display: inline-block;
    animation: typingBounce 1.2s infinite ease-in-out;
}
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typingBounce {
    0%, 80%, 100% { transform: translateY(0); opacity: 0.5; }
    40%            { transform: translateY(-6px); opacity: 1; }
}

/* Welcome chips */
.quick-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 1rem; }
.chip {
    background: #f0eaff;
    color: #7c3aed;
    border: 1px solid #ddd6fe;
    border-radius: 20px;
    padding: 0.35rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.chip:hover { background: #7c3aed; color: #fff; border-color: #7c3aed; }

/* Input bar */
#chatFooter {
    padding: 1rem 1.25rem;
    background: #fff;
    border-top: 1px solid #e9ecef;
}
#chatInput {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: 999px;
    padding: 0.7rem 1.25rem;
    font-size: 0.92rem;
    outline: none;
    transition: border-color .2s;
    resize: none;
    font-family: inherit;
    max-height: 120px;
    overflow-y: auto;
}
#chatInput:focus { border-color: #7c3aed; }

#sendBtn {
    width: 46px; height: 46px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: transform .15s, box-shadow .15s;
    box-shadow: 0 4px 12px rgba(124,58,237,0.35);
}
#sendBtn:hover  { transform: scale(1.08); box-shadow: 0 6px 16px rgba(124,58,237,0.45); }
#sendBtn:active { transform: scale(0.95); }
#sendBtn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* Chat header */
#chatHeader {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    border-radius: 16px 16px 0 0;
    display: flex;
    align-items: center;
    gap: 14px;
}
.chat-avatar-main {
    width: 50px; height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    color: #fff;
    backdrop-filter: blur(4px);
}
.status-dot {
    width: 9px; height: 9px;
    background: #4ade80;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(74,222,128,0.35);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 2px rgba(74,222,128,0.35); }
    50%       { box-shadow: 0 0 0 5px rgba(74,222,128,0.12); }
}

/* Main card */
#chatCard {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: none;
    display: flex;
    flex-direction: column;
}

/* Bot message markdown-like formatting */
.bot-bubble ul  { padding-left: 1.2rem; margin: 0.4rem 0 0; }
.bot-bubble li  { margin-bottom: 0.25rem; }
.bot-bubble strong { color: #5b21b6; }
</style>

<div class="row justify-content-center" style="height: calc(100vh - 145px);">
    <div class="col-lg-9 d-flex flex-column" style="height: 100%;">

        <!-- Page heading -->
        <div class="mb-3">
            <h4 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-robot me-2 text-purple" style="color:#7c3aed;"></i>
                PAG-AMUMA Chatbot
            </h4>
            <p class="text-muted small mb-0">AI-powered pregnancy support — ask in English, Tagalog, or Hiligaynon.</p>
        </div>

        <!-- Chat Card -->
        <div id="chatCard" class="flex-grow-1 d-flex flex-column">

            <!-- Header -->
            <div id="chatHeader">
                <div class="chat-avatar-main">🤱</div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold text-white mb-0">PAG-AMUMA Assistant</h6>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="status-dot"></span>
                        <small class="text-white-50 fw-medium" style="font-size:0.75rem;">Online &amp; Ready</small>
                    </div>
                </div>
                <button id="clearChatBtn" class="btn btn-sm" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:8px; font-size:0.78rem;" title="Start a new conversation">
                    <i class="fa-solid fa-rotate-right me-1"></i> New Chat
                </button>
            </div>

            <!-- Chat Window -->
            <div id="chatWindow" class="flex-grow-1">

                <!-- Welcome bot message -->
                <div class="msg-row bot-row" id="welcomeMsg">
                    <div class="msg-avatar bot-avatar"><i class="fa-solid fa-robot"></i></div>
                    <div>
                        <div class="msg-bubble bot-bubble">

                            <p class="mb-2 text-muted" style="font-size:0.88rem;">

                                I'm your PAG-AMUMA pregnancy assistant. I can answer your questions about prenatal care, fetal development, nutrition, and emotional wellness — in <strong>English</strong>, <strong>Tagalog</strong>, or <strong>Hiligaynon</strong>!
                            </p>
                            <p class="mb-2" style="font-size:0.88rem; color:#374151;">Try one of these to get started:</p>
                            <div class="quick-chips">
                                <span class="chip" onclick="sendQuick(this)">🥦 Foods to eat in pregnancy</span>
                                <span class="chip" onclick="sendQuick(this)">😴 First trimester fatigue</span>
                                <span class="chip" onclick="sendQuick(this)">🤰 Signs of labor</span>
                                <span class="chip" onclick="sendQuick(this)">💊 Prenatal vitamins</span>
                                <span class="chip" onclick="sendQuick(this)">🧘 Stress during pregnancy</span>
                                <span class="chip" onclick="sendQuick(this)">👶 Baby movements — normal?</span>
                            </div>
                            <small class="msg-time"><?= date('h:i A') ?></small>
                        </div>
                    </div>
                </div>

            </div><!-- /#chatWindow -->

            <!-- Input Footer -->
            <div id="chatFooter">
                <div class="d-flex align-items-end gap-2">
                    <textarea
                        id="chatInput"
                        rows="1"
                        placeholder="Ask your pregnancy question here…"
                        maxlength="1000"
                    ></textarea>
                    <button id="sendBtn" title="Send message">
                        <i class="fa-solid fa-paper-plane" style="font-size:0.9rem;"></i>
                    </button>
                </div>
                <small class="text-muted d-block mt-1 ms-2" style="font-size:0.7rem;">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    For emergencies, always contact your healthcare provider immediately.
                </small>
            </div>

        </div><!-- /#chatCard -->
    </div>
</div>

<script>
(function () {
    const chatWindow = document.getElementById('chatWindow');
    const chatInput  = document.getElementById('chatInput');
    const sendBtn    = document.getElementById('sendBtn');
    const clearBtn   = document.getElementById('clearChatBtn');

    let sessionId = null;
    let isSending = false;

    // Load History
    fetch('/pagamuma/api/get_chatbot_history.php')
        .then(res => res.json())
        .then(data => {
            if (data.session_id) sessionId = data.session_id;
            if (data.messages && data.messages.length > 0) {
                // Hide default welcome if returning user
                document.getElementById('welcomeMsg').style.display = 'none';
                data.messages.forEach(msg => {
                    let time = new Date(msg.sent_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                    appendMessage(msg.sender, msg.text, time);
                });
            }
        });

    // --- Auto-resize textarea ---
    chatInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // --- Send on Enter (Shift+Enter = new line) ---
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    sendBtn.addEventListener('click', handleSend);
    clearBtn.addEventListener('click', clearChat);

    // --- Quick chip shortcut ---
    window.sendQuick = function (el) {
        const text = el.textContent.replace(/^[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF} ]+/u, '').trim();
        chatInput.value = text;
        handleSend();
    };

    function handleSend() {
        if (isSending) return;
        const message = chatInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        chatInput.value = '';
        chatInput.style.height = 'auto';

        const typingEl = appendTyping();
        isSending = true;
        sendBtn.disabled = true;

        fetch('/pagamuma/api/chatbot_action.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ message: message, session_id: sessionId })
        })
        .then(res => res.json())
        .then(data => {
            removeTyping(typingEl);
            if (data.error) {
                appendMessage('bot', '⚠️ ' + data.error);
            } else {
                sessionId = data.session_id;
                appendMessage('bot', data.reply);
            }
        })
        .catch(() => {
            removeTyping(typingEl);
            appendMessage('bot', '⚠️ Could not connect to the AI service. Please check your internet connection and try again.');
        })
        .finally(() => {
            isSending = false;
            sendBtn.disabled = false;
            chatInput.focus();
        });
    }

    function appendMessage(sender, text, time = null) {
        const isUser  = sender === 'user';
        const row     = document.createElement('div');
        row.className = 'msg-row ' + (isUser ? 'user-row' : 'bot-row');

        const avatar     = document.createElement('div');
        avatar.className = 'msg-avatar ' + (isUser ? 'user-avatar' : 'bot-avatar');
        avatar.innerHTML = isUser
            ? '<i class="fa-solid fa-user" style="font-size:0.75rem;"></i>'
            : '<i class="fa-solid fa-robot" style="font-size:0.75rem;"></i>';

        const bubble     = document.createElement('div');
        bubble.className = 'msg-bubble ' + (isUser ? 'user-bubble' : 'bot-bubble');

        const t = time ? time : getCurrentTime();
        
        // Basic markdown-like formatting for bot messages
        const formatted = isUser ? escapeHtml(text) : formatBotText(text);
        bubble.innerHTML = formatted + '<small class="msg-time">' + t + '</small>';

        row.appendChild(avatar);
        row.appendChild(bubble);
        chatWindow.appendChild(row);
        scrollToBottom();
    }

    function appendTyping() {
        const row     = document.createElement('div');
        row.className = 'msg-row bot-row';
        row.id        = 'typingRow';

        const avatar     = document.createElement('div');
        avatar.className = 'msg-avatar bot-avatar';
        avatar.innerHTML = '<i class="fa-solid fa-robot" style="font-size:0.75rem;"></i>';

        const bubble     = document.createElement('div');
        bubble.className = 'msg-bubble bot-bubble';
        bubble.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';

        row.appendChild(avatar);
        row.appendChild(bubble);
        chatWindow.appendChild(row);
        scrollToBottom();
        return row;
    }

    function removeTyping(el) {
        if (el && el.parentNode) el.parentNode.removeChild(el);
    }

    function scrollToBottom() {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    function getCurrentTime() {
        return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>');
    }

    function formatBotText(text) {
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        const lines = text.split('\n');
        let html = '';
        let inList = false;
        for (let line of lines) {
            const bullet = line.match(/^[\-\*•]\s+(.+)/);
            if (bullet) {
                if (!inList) { html += '<ul>'; inList = true; }
                html += '<li>' + bullet[1] + '</li>';
            } else {
                if (inList) { html += '</ul>'; inList = false; }
                html += (line.trim() ? '<p style="margin:0 0 0.4rem;">' + line + '</p>' : '');
            }
        }
        if (inList) html += '</ul>';
        return html;
    }

    function clearChat() {
        const rows = chatWindow.querySelectorAll('.msg-row:not(#welcomeMsg)');
        rows.forEach(r => r.remove());
        document.getElementById('welcomeMsg').style.display = 'flex';
        sessionId = null;
    }

})();
</script>
