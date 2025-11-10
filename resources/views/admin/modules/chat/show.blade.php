@extends('admin.master')

@section('module', 'Chi tiết phiên chat')
@section('content')
    <div class="page-content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Chi tiết phiên chat</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">
                                @if($session->user)
                                    <i class="ri-user-line"></i> {{ $session->user->fullname }}
                                @else
                                    <i class="ri-user-line"></i> {{ $session->guest_name }}
                                @endif
                            </h5>
                            <small class="text-muted">
                                @if($session->user)
                                    Email: {{ $session->user->email }}
                                @else
                                    Email: {{ $session->guest_email }}
                                @endif
                                | Bắt đầu: {{ $session->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div>
                            @if($session->status === 'active')
                                <span class="badge bg-success">Đang hoạt động</span>
                                <form action="{{ route('admin.chat.close', $session->uuid) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger ms-2" onclick="return confirm('Bạn có chắc muốn đóng phiên chat này?')">
                                        <i class="ri-close-line"></i> Đóng phiên
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-secondary">Đã đóng</span>
                            @endif
                            <a href="{{ route('admin.chat.index') }}" class="btn btn-sm btn-secondary ms-2">
                                <i class="ri-arrow-left-line"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Chat Messages Area -->
                    <div class="chat-conversation" style="height: 500px; overflow-y: auto; padding: 20px; background: #f8f9fa;" id="chat-messages-container">
                        <div id="chat-messages">
                            @foreach($session->messages as $message)
                                <div class="message-item {{ $message->sender_type === 'admin' ? 'message-admin' : 'message-user' }}" data-message-id="{{ $message->id }}">
                                    <div class="message-content">
                                        <div class="message-header">
                                            <strong>
                                                @if($message->sender_type === 'admin')
                                                    <i class="ri-shield-user-line text-primary"></i> 
                                                    {{ $message->sender ? $message->sender->fullname : 'Admin' }}
                                                @else
                                                    <i class="ri-user-line text-success"></i> 
                                                    @if($session->user)
                                                        {{ $session->user->fullname }}
                                                    @else
                                                        {{ $session->guest_name }}
                                                    @endif
                                                @endif
                                            </strong>
                                            <small class="text-muted ms-2">
                                                {{ $message->created_at->format('H:i d/m/Y') }}
                                            </small>
                                        </div>
                                        <div class="message-text">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Message Input -->
                    @if($session->status === 'active')
                    <div class="chat-input-section" style="border-top: 2px solid #e9ecef; padding: 20px; background: white;">
                        <form id="send-message-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="message-input" 
                                       placeholder="Nhập tin nhắn..." 
                                       maxlength="1000"
                                       autocomplete="off">
                                <button class="btn btn-primary" type="submit" id="send-button">
                                    <i class="ri-send-plane-fill"></i> Gửi
                                </button>
                            </div>
                            <small class="text-muted">Nhấn Enter để gửi tin nhắn</small>
                        </form>
                    </div>
                    @else
                    <div class="alert alert-info m-3">
                        <i class="ri-information-line"></i> Phiên chat này đã được đóng. Không thể gửi tin nhắn mới.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
.chat-conversation {
    position: relative;
}

#chat-messages {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.message-item {
    display: flex;
    margin-bottom: 10px;
}

.message-item.message-admin {
    justify-content: flex-end;
}

.message-item.message-user {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.message-item.message-admin .message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-item.message-user .message-content {
    background: white;
    color: #333;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
}

.message-header {
    margin-bottom: 6px;
    font-size: 13px;
}

.message-item.message-admin .message-header {
    color: rgba(255,255,255,0.9);
}

.message-text {
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
}

.chat-input-section {
    background: white;
}

/* Scrollbar styling */
.chat-conversation::-webkit-scrollbar {
    width: 6px;
}

.chat-conversation::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-conversation::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.chat-conversation::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* New message animation */
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

.message-item.new-message {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const sessionUuid = '{{ $session->uuid }}';
    const isActive = {{ $session->status === 'active' ? 'true' : 'false' }};
    let lastMessageId = {{ $session->messages->last() ? $session->messages->last()->id : 0 }};
    let pollingInterval = null;
    let isSending = false;

    

    // Auto scroll to bottom on page load
    scrollToBottom();

    // Send message form
    $('#send-message-form').on('submit', function(e) {
        e.preventDefault();
        
        const message = $('#message-input').val().trim();
        
        if (!message || isSending) {
            return;
        }

        isSending = true;
        $('#message-input').prop('disabled', true);
        $('#send-button').prop('disabled', true);

        

        $.ajax({
            url: '{{ route("admin.chat.send", $session->uuid) }}',
            method: 'POST',
            data: {
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (!messageExists(response.message.id)) {
                        addMessageToUI(response.message, 'admin');
                        lastMessageId = response.message.id;
                    }
                    $('#message-input').val('');
                    scrollToBottom();
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('❌ Error sending message:', xhr);
                let errorMsg = 'Có lỗi xảy ra khi gửi tin nhắn!';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                alert(errorMsg);
            },
            complete: function() {
                $('#message-input').prop('disabled', false);
                $('#send-button').prop('disabled', false);
                $('#message-input').focus();
                isSending = false;
            }
        });
    });

    // Enter key to send
    $('#message-input').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#send-message-form').submit();
        }
    });

    // Polling for new messages (only if session is active)
    if (isActive) {
        startPolling();
    }

    function startPolling() {
        
        
        // Clear any existing interval
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        pollingInterval = setInterval(function() {
            if (!isSending) {
                pollNewMessages();
            }
        }, 3000); // Poll every 3 seconds
        
        
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function pollNewMessages() {
        
        $.ajax({
            url: '{{ route("admin.chat.messages", $session->uuid) }}',
            method: 'GET',
            data: {
                last_message_id: lastMessageId
            },
            success: function(response) {
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(function(message) {
                            if (!messageExists(message.id)) {
                                addMessageToUI(message, 'user', true);
                                lastMessageId = Math.max(lastMessageId, message.id);
                            }
                        });
                    
                    scrollToBottom();
                    // Update unread count in header if exists
                    if (response.unread_count !== undefined) {
                        updateUnreadCount(response.unread_count);
                    }
                } else {
                }
            },
            error: function(xhr) {
                console.error('❌ Admin poll error:', xhr.status);
            }
        });
    }

    function messageExists(messageId) {
        return $(`[data-message-id="${messageId}"]`).length > 0;
    }

    function addMessageToUI(message, type, isNew = false) {
        if (messageExists(message.id)) {
            return;
        }

        const messageTime = formatDateTime(message.created_at);
        const senderName = message.sender ? message.sender.fullname : 
                          (type === 'admin' ? 'Admin' : '{{ $session->user ? $session->user->fullname : $session->guest_name }}');
        
        const messageHtml = `
            <div class="message-item message-${type} ${isNew ? 'new-message' : ''}" data-message-id="${message.id}">
                <div class="message-content">
                    <div class="message-header">
                        <strong>
                            ${type === 'admin' ? 
                                '<i class="ri-shield-user-line text-primary"></i>' : 
                                '<i class="ri-user-line text-success"></i>'
                            } 
                            ${senderName}
                        </strong>
                        <small class="text-muted ms-2">
                            ${messageTime}
                        </small>
                    </div>
                    <div class="message-text">
                        ${escapeHtml(message.message)}
                    </div>
                </div>
            </div>
        `;
        
        $('#chat-messages').append(messageHtml);
    }

    function scrollToBottom() {
        const container = $('#chat-messages-container');
        container.animate({
            scrollTop: container[0].scrollHeight
        }, 300);
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        
        return `${hours}:${minutes} ${day}/${month}/${year}`;
    }

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

    function updateUnreadCount(count) {
        const badge = $('.chat-unread-badge');
        if (badge.length) {
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }
    }

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        stopPolling();
    });

    // BỎ window blur/focus - Để polling chạy liên tục
    // Admin cần nhận tin nhắn realtime ngay cả khi đang ở tab khác
});
</script>
@endsection