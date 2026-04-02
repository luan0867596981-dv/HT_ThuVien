<!-- 100% Safe Rule-Based AI Chatbot UI -->
<style>
    @keyframes bot-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    #safe-chatbot-wrapper {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        pointer-events: none; /* BẮT BUỘC: Cho phép click xuyên qua toàn bộ màn hình */
        z-index: 1050;
    }

    #chatbot-trigger {
        position: fixed;
        bottom: 30px; right: 30px;
        width: 56px; height: 56px;
        background: var(--midnight-slate);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        cursor: pointer;
        pointer-events: auto; /* Cho phép click vào nút */
        border: 1px solid rgba(255,255,255,0.1);
        z-index: 1060;
        transition: all 0.3s;
    }
    #chatbot-trigger:hover { transform: scale(1.1) rotate(5deg); }

    .chatbot-ui-container {
        position: fixed;
        bottom: 100px; right: 30px;
        width: 360px; height: 500px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        display: flex; flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        opacity: 0; visibility: hidden; transform: translateY(20px);
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        pointer-events: auto; /* Cho phép tương tác với cửa sổ chat */
        z-index: 1061;
    }

    .chatbot-ui-container.active {
        opacity: 1; visibility: visible; transform: translateY(0);
    }

    .chat-header {
        background: var(--midnight-slate);
        color: white; padding: 15px 20px;
        display: flex; justify-content: space-between; align-items: center;
    }

    .chat-body {
        flex: 1; padding: 20px; overflow-y: auto;
        background-color: #f8fafc; display: flex; flex-direction: column; gap: 12px;
    }

    .msg-bubble {
        max-width: 85%; padding: 10px 14px; border-radius: 14px; font-size: 0.85rem; line-height: 1.4;
    }
    .msg-bot { align-self: flex-start; background: #e2e8f0; color: #1e293b; }
    .msg-user { align-self: flex-end; background: var(--accent-indigo); color: white; }

    .chat-footer {
        padding: 15px; border-top: 1px solid #e2e8f0; background: white;
        display: flex; gap: 8px;
    }
    .chat-input {
        flex: 1; border: 1px solid #e2e8f0; border-radius: 10px;
        padding: 8px 12px; font-size: 0.85rem; outline: none;
    }
    .chat-send {
        background: var(--midnight-slate); color: white; border: none;
        border-radius: 10px; width: 40px; display: flex; align-items: center; justify-content: center;
    }
</style>

<div id="safe-chatbot-wrapper">
    <div id="chatbot-trigger">
        <i class="fa-solid fa-robot fs-5"></i>
    </div>

    <div id="chatbot-container" class="chatbot-ui-container">
        <div class="chat-header">
            <div class="fw-bold"><i class="fa-solid fa-bolt me-2 text-warning"></i> AI Assistant</div>
            <button id="chatbot-close" class="btn btn-close btn-close-white btn-sm shadow-none"></button>
        </div>
        <div class="chat-body" id="chat-messages">
            <div class="msg-bubble msg-bot">Xin chào! Tôi có thể giúp gì cho bạn hôm nay?</div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input-field" class="chat-input" placeholder="Hỏi tôi về quy định mượn sách..." autocomplete="off">
            <button id="chat-send-btn" class="chat-send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('chatbot-trigger');
    const container = document.getElementById('chatbot-container');
    const closeBtn = document.getElementById('chatbot-close');
    const sendBtn = document.getElementById('chat-send-btn');
    const inputField = document.getElementById('chat-input-field');
    const messagesBox = document.getElementById('chat-messages');

    trigger.addEventListener('click', () => {
        container.classList.toggle('active');
        if(container.classList.contains('active')) inputField.focus();
    });

    closeBtn.addEventListener('click', () => container.classList.remove('active'));

    function addMessage(text, role) {
        const div = document.createElement('div');
        div.className = `msg-bubble msg-${role}`;
        div.innerHTML = text;
        messagesBox.appendChild(div);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    async function handleChat() {
        const msg = inputField.value.trim();
        if(!msg) return;

        addMessage(msg, 'user');
        inputField.value = '';

        // AJAX Rule-based Processing
        try {
            const formData = new FormData();
            formData.append('message', msg);
            const response = await fetch('index.php?controller=chatbot&action=ask', { method: 'POST', body: formData });
            const reply = await response.text();
            addMessage(reply, 'bot');
        } catch(e) {
            addMessage("Tôi đang gặp sự cố kết nối. Hãy thử lại sau.", 'bot');
        }
    }

    sendBtn.addEventListener('click', handleChat);
    inputField.addEventListener('keypress', (e) => { if(e.key === 'Enter') handleChat(); });
});
</script>
