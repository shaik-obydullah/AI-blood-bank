@extends('website.master')

@section('title', 'AI Assistant - BloodBank')

@section('home')
<style>
    .chat-page {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 15px;
    }

    .chat-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--white);
        padding: 30px;
        border-radius: 12px 12px 0 0;
        text-align: center;
    }

    .chat-header .ai-icon {
        font-size: 48px;
        margin-bottom: 10px;
        animation: pulse 2s infinite;
    }

    .chat-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .chat-header p {
        margin: 8px 0 0;
        opacity: 0.85;
        font-size: 14px;
    }

    .chat-container {
        background: var(--white);
        border: 1px solid var(--border);
        border-top: none;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
    }

    .chat-messages {
        height: 450px;
        overflow-y: auto;
        padding: 20px;
        background: var(--light-gray);
        scroll-behavior: smooth;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .message {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        animation: fadeIn 0.3s ease;
    }

    .message.user {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        color: var(--white);
    }

    .message.ai .message-avatar {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }

    .message.user .message-avatar {
        background: linear-gradient(135deg, var(--accent), #1565c0);
    }

    .message-bubble {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.6;
        word-wrap: break-word;
    }

    .message.ai .message-bubble {
        background: var(--white);
        border: 1px solid var(--border);
        border-bottom-left-radius: 4px;
        color: var(--text);
    }

    .message.user .message-bubble {
        background: var(--primary);
        color: var(--white);
        border-bottom-right-radius: 4px;
    }

    .message-time {
        font-size: 11px;
        color: var(--text-light);
        margin-top: 4px;
        opacity: 0.7;
    }

    .message.user .message-time {
        text-align: right;
    }

    .typing-indicator {
        display: none;
        padding: 10px 20px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-left: 52px;
        margin-bottom: 20px;
        width: fit-content;
        animation: fadeIn 0.3s ease;
    }

    .typing-indicator.active {
        display: block;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .typing-dots span {
        width: 8px;
        height: 8px;
        background: var(--text-light);
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    .chat-input-area {
        padding: 16px 20px;
        background: var(--white);
        border-top: 1px solid var(--border);
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }

    .chat-input-area textarea {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: 24px;
        font-size: 14px;
        font-family: inherit;
        resize: none;
        max-height: 100px;
        min-height: 44px;
        line-height: 1.4;
        transition: border-color 0.3s, box-shadow 0.3s;
        outline: none;
    }

    .chat-input-area textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
    }

    .send-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--primary);
        color: var(--white);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: background 0.3s, transform 0.2s;
        flex-shrink: 0;
    }

    .send-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }

    .send-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .suggestions {
        padding: 12px 20px;
        background: var(--white);
        border-top: 1px solid var(--border);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .suggestion-chip {
        padding: 6px 14px;
        background: var(--light-gray);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 12px;
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
    }

    .suggestion-chip:hover {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
    }

    .welcome-message {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-light);
    }

    .welcome-message i {
        font-size: 64px;
        color: var(--primary);
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .welcome-message h3 {
        margin: 0 0 8px;
        color: var(--text);
    }

    .welcome-message p {
        margin: 0;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .chat-page {
            margin: 15px auto;
            padding: 0 10px;
        }

        .chat-messages {
            height: 350px;
        }

        .message-bubble {
            max-width: 85%;
        }
    }
</style>

<div class="chat-page">
    <div class="chat-header">
        <div class="ai-icon"><i class="fas fa-robot"></i></div>
        <h2>AI Blood Bank Assistant</h2>
        <p>Ask about donor eligibility, blood availability, or donation guidelines</p>
    </div>

    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <div class="welcome-message" id="welcomeMessage">
                <i class="fas fa-comment-medical"></i>
                <h3>How can I help you today?</h3>
                <p>Ask me anything about blood donation, eligibility requirements, or available blood types.</p>
            </div>
        </div>

        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <span></span><span></span><span></span>
            </div>
        </div>

        <div class="suggestions" id="suggestions">
            <span class="suggestion-chip" onclick="askSuggestion(this)">What is the minimum hemoglobin to donate?</span>
            <span class="suggestion-chip" onclick="askSuggestion(this)">How often can I donate blood?</span>
            <span class="suggestion-chip" onclick="askSuggestion(this)">Who is eligible to donate blood?</span>
            <span class="suggestion-chip" onclick="askSuggestion(this)">What happens after donating blood?</span>
        </div>

        <div class="chat-input-area">
            <textarea id="chatInput" rows="1" placeholder="Type your question..." onkeydown="handleKeydown(event)"></textarea>
            <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');
    const welcomeMessage = document.getElementById('welcomeMessage');

    function getCurrentTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function addMessage(text, sender) {
        if (welcomeMessage) {
            welcomeMessage.style.display = 'none';
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;

        const avatar = sender === 'ai' ? 'fa-robot' : 'fa-user';
        const avatarBg = sender === 'ai' ? 'ai' : 'user';

        messageDiv.innerHTML = `
            <div class="message-avatar"><i class="fas ${avatar}"></i></div>
            <div>
                <div class="message-bubble">${escapeHtml(text)}</div>
                <div class="message-time">${getCurrentTime()}</div>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

    function setLoading(loading) {
        sendBtn.disabled = loading;
        chatInput.disabled = loading;
        if (loading) {
            typingIndicator.classList.add('active');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        } else {
            typingIndicator.classList.remove('active');
        }
    }

    async function sendMessage() {
        const question = chatInput.value.trim();
        if (!question) return;

        addMessage(question, 'user');
        chatInput.value = '';
        chatInput.style.height = 'auto';
        setLoading(true);

        try {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: `question=${encodeURIComponent(question)}`
            });

            const data = await response.json();

            if (data.answer) {
                addMessage(data.answer, 'ai');
            } else if (data.message) {
                addMessage(data.message, 'ai');
            } else {
                addMessage('Sorry, I received an unexpected response. Please try again.', 'ai');
            }
        } catch (error) {
            addMessage('Sorry, I could not connect to the AI service. Please try again later.', 'ai');
        } finally {
            setLoading(false);
            chatInput.focus();
        }
    }

    function handleKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function askSuggestion(chip) {
        chatInput.value = chip.textContent;
        sendMessage();
    }

    // Auto-resize textarea
    chatInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
</script>
@endsection
