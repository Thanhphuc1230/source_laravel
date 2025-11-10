<!-- Chat Widget -->
<div id="chat-widget" class="chat-widget">
    <div class="chat-widget-toggle" id="chat-toggle">
        <i class="ri-chat-1-line"></i>
        <span class="chat-notification" id="chat-notification" style="display: none;"></span>
    </div>

    <div class="chat-widget-container" id="chat-container" style="display: none;">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <div class="avatar-sm me-2">
                    <span class="avatar-title bg-primary rounded-circle">
                        <i class="ri-customer-service-line"></i>
                    </span>
                </div>
                <div>
                    <h6 class="mb-0">Hỗ trợ trực tuyến</h6>
                    <small class="text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn</small>
                </div>
            </div>
            <button class="btn btn-sm btn-link text-white p-0" id="chat-close">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <!-- Chat Body -->
        <div class="chat-body" id="chat-body">
            <div class="chat-messages" id="chat-messages">
                <!-- Messages will be loaded here -->
            </div>
        </div>

        <!-- Guest Info Form (for non-logged users) -->
        <div class="guest-info" id="guest-info" style="display: none;">
            <div class="p-3">
                <h6 class="mb-3">Vui lòng cung cấp thông tin</h6>
                <form id="guest-form">
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" id="guest-name" placeholder="Họ tên" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" class="form-control form-control-sm" id="guest-email" placeholder="Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Bắt đầu chat</button>
                </form>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="chat-input" id="chat-input" style="display: none;">
            <form id="message-form">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="message-text" placeholder="Nhập tin nhắn..." maxlength="500" autocomplete="off">
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="ri-send-plane-line"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
    font-family: 'Inter', sans-serif;
}

.chat-widget-toggle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    position: relative;
}

.chat-widget-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.chat-widget-toggle i {
    font-size: 24px;
}

.chat-notification {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.chat-widget-container {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-header .avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
}

.chat-messages {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.message {
    max-width: 80%;
    padding: 8px 12px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.message.user {
    align-self: flex-end;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message.admin {
    align-self: flex-start;
    background: white;
    color: #333;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 2px;
}

.message.new-message {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-input {
    border-top: 1px solid #e9ecef;
    padding: 15px;
    background: white;
}

.chat-input .input-group .form-control {
    border-radius: 20px 0 0 20px;
    border: 1px solid #e9ecef;
}

.chat-input .input-group .btn {
    border-radius: 0 20px 20px 0;
    border-left: none;
}

.guest-info {
    border-top: 1px solid #e9ecef;
    background: white;
}

/* Scrollbar styling */
.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

@media (max-width: 480px) {
    .chat-widget-container {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 80px;
        right: 20px;
    }
}
</style>

<!-- Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    console.log('🚀 Chat widget initialized');

    let sessionId = null;
    let lastPolledMessageId = 0;
    let isSending = false;
    let pollingInterval = null;
    let isLoggedIn = false;
    let userInfo = {
        name: '',
        email: ''
    };

    // Check if user is logged in
    if (typeof window.userData !== 'undefined') {
        isLoggedIn = window.userData.isLoggedIn || false;
        userInfo = window.userData.userInfo || userInfo;
    }

    console.log('👤 User logged in:', isLoggedIn);

    // Toggle chat widget
    $('#chat-toggle').on('click', function() {
        console.log('💬 Chat toggle clicked');
        const container = $('#chat-container');
        const isVisible = container.is(':visible');

        if (isVisible) {
            container.fadeOut(200);
        } else {
            container.fadeIn(200);
            if (!sessionId) {
                initializeChat();
            }
        }
    });

    // Close chat
    $('#chat-close').on('click', function() {
        console.log('❌ Chat close clicked');
        $('#chat-container').fadeOut(200);
        stopPolling();
    });

    // Initialize chat
    function initializeChat() {
        console.log('🔧 Initializing chat');
        if (isLoggedIn) {
            startChatSession({
                user_id: userInfo.id || null
            });
        } else {
            $('#guest-info').show();
            $('#chat-body').hide();
            $('#chat-input').hide();
        }
    }

    // Guest form submission
    $('#guest-form').on('submit', function(e) {
        e.preventDefault();
        console.log('📝 Guest form submitted');

        const name = $('#guest-name').val().trim();
        const email = $('#guest-email').val().trim();

        if (!name || !email) {
            alert('Vui lòng nhập đầy đủ thông tin!');
            return;
        }

        startChatSession({
            guest_name: name,
            guest_email: email
        });
    });

    // Start chat session
    function startChatSession(data) {
        console.log('🎬 Starting chat session with data:', data);

        $.ajax({
            url: '/api/chat/start',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            success: function(response) {
                console.log('✅ Chat session started:', response);
                sessionId = response.session_id;
                $('#guest-info').hide();
                $('#chat-body').show();
                $('#chat-input').show();
                loadMessages();
                startPolling();
            },
            error: function(xhr, status, error) {
                console.error('❌ Error starting chat session:', xhr.responseText);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });
    }

    // Send message
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        
        const message = $('#message-text').val().trim();
        if (!message || !sessionId || isSending) {
            return;
        }

        isSending = true;
        $('#message-text').prop('disabled', true);

        console.log('📤 Sending message:', message);

        $.ajax({
            url: '/api/chat/send',
            method: 'POST',
            data: {
                session_id: sessionId,
                message: message
            },
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            success: function(response) {
                console.log('✅ Message sent:', response);
                
                if (response.success && response.message && response.message.id) {
                    if (!messageExists(response.message.id)) {
                        addMessage('user', message, null, response.message.id);
                        lastPolledMessageId = Math.max(lastPolledMessageId, response.message.id);
                        console.log('📊 Updated lastPolledMessageId to:', lastPolledMessageId);
                    }
                }
                
                $('#message-text').val('');
            },
            error: function(xhr, status, error) {
                console.error('❌ Error sending message:', xhr.responseText);
                alert('Có lỗi xảy ra khi gửi tin nhắn!');
            },
            complete: function() {
                $('#message-text').prop('disabled', false);
                $('#message-text').focus();
                isSending = false;
            }
        });
    });

    // Load messages
    function loadMessages() {
        if (!sessionId) {
            console.log('⚠️ No session ID for loading messages');
            return;
        }

        console.log('📥 Loading messages for session:', sessionId);

        $.ajax({
            url: '/api/chat/messages',
            method: 'GET',
            data: { session_id: sessionId },
            success: function(response) {
                console.log('✅ Messages loaded:', response);
                $('#chat-messages').empty();
                
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(function(msg) {
                        addMessage(msg.sender_type, msg.message, msg.created_at, msg.id);
                        lastPolledMessageId = Math.max(lastPolledMessageId, msg.id);
                    });
                    console.log('📊 Final lastPolledMessageId after load:', lastPolledMessageId);
                }
                scrollToBottom();
            },
            error: function(xhr, status, error) {
                console.error('❌ Error loading messages:', xhr.responseText);
            }
        });
    }

    // Check if message already exists
    function messageExists(messageId) {
        return $(`[data-message-id="${messageId}"]`).length > 0;
    }

    // Add message to UI
    function addMessage(senderType, message, timestamp = null, messageId = null, isNew = false) {
        if (messageId && messageExists(messageId)) {
            console.log('⚠️ Message already exists, skipping:', messageId);
            return;
        }

        const time = timestamp ? 
            new Date(timestamp).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'}) : 
            new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
        
        const messageHtml = `
            <div class="message ${senderType} ${isNew ? 'new-message' : ''}" ${messageId ? `data-message-id="${messageId}"` : ''}>
                ${escapeHtml(message)}
                <div class="message-time">${time}</div>
            </div>
        `;
        $('#chat-messages').append(messageHtml);
        scrollToBottom();
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Scroll to bottom
    function scrollToBottom() {
        const chatBody = $('#chat-body');
        chatBody.scrollTop(chatBody[0].scrollHeight);
    }

    // Polling for new messages
    function startPolling() {
        console.log('🔄 Starting polling for session:', sessionId);
        
        // Clear any existing interval first
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        pollingInterval = setInterval(function() {
            if (sessionId && !isSending) {
                console.log('📡 Polling... last_message_id:', lastPolledMessageId);
                
                $.ajax({
                    url: '/api/chat/poll',
                    method: 'GET',
                    data: { 
                        session_id: sessionId,
                        last_message_id: lastPolledMessageId
                    },
                    success: function(response) {
                        if (response.success && response.messages && response.messages.length > 0) {
                            console.log('📬 New admin messages received:', response.messages.length);
                            
                            response.messages.forEach(function(msg) {
                                console.log('  📨 Message ID:', msg.id, '| Content:', msg.message);
                                
                                if (!messageExists(msg.id)) {
                                    console.log('  ✅ Adding new admin message');
                                    addMessage('admin', msg.message, msg.created_at, msg.id, true);
                                    lastPolledMessageId = Math.max(lastPolledMessageId, msg.id);
                                    console.log('  📊 Updated lastPolledMessageId to:', lastPolledMessageId);
                                } else {
                                    console.log('  ⚠️ Message already exists');
                                }
                            });
                        } else {
                            console.log('📭 No new admin messages');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Poll error:', xhr.status, error);
                    }
                });
            }
        }, 3000); // Poll every 3 seconds
        
        console.log('✅ Polling started');
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
            console.log('⏹️ Polling stopped');
        }
    }

    // Enter to send
    $('#message-text').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#message-form').submit();
        }
    });

    // Get CSRF token function
    function getCsrfToken() {
        let token = $('meta[name="csrf-token"]').attr('content');
        if (token) return token;

        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'XSRF-TOKEN') {
                return decodeURIComponent(value);
            }
        }

        return '';
    }

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        stopPolling();
    });

    // BỎ window blur/focus - Polling chạy liên tục để nhận tin nhắn realtime
});
</script>