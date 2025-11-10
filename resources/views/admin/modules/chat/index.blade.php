@extends('admin.master')
@section('module', 'Quản lý Chat')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý Chat</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Chat</li>
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
                                <h5 class="card-title mb-0">
                                    <i class="ri-message-3-line"></i> Danh sách phiên chat
                                    <span class="badge bg-primary ms-2" id="total-sessions">{{ $sessions->total() }}</span>
                                    <span class="badge bg-danger ms-2" id="unread-badge"
                                        style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">{{ $unreadCount }}</span>
                                </h5>
                                <div>
                                    <button class="btn btn-sm btn-info" id="refresh-list">
                                        <i class="ri-refresh-line"></i> Làm mới
                                    </button>
                                    <form action="{{ route('admin.chat.bulk-close') }}" method="POST" class="d-inline"
                                        id="bulk-close-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" id="bulk-close-btn" disabled>
                                            <i class="ri-close-line"></i> Đóng đã chọn
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Status Filter -->
                            <div class="mb-3">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary status-filter active"
                                        data-status="all">
                                        Tất cả
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success status-filter"
                                        data-status="active">
                                        Đang hoạt động
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary status-filter"
                                        data-status="closed">
                                        Đã đóng
                                    </button>
                                </div>
                                <span class="ms-3 text-muted" id="last-update">
                                    Cập nhật lần cuối: <span id="last-update-time">{{ now()->format('H:i:s') }}</span>
                                </span>
                            </div>

                            <!-- Chat Sessions List -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="chat-sessions-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" class="form-check-input" id="select-all">
                                            </th>
                                            <th>Người dùng</th>
                                            <th>Tin nhắn cuối</th>
                                            <th width="120">Trạng thái</th>
                                            <th width="150">Thời gian</th>
                                            <th width="100">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sessions-tbody">
                                        @forelse($sessions as $session)
                                            <tr data-session-id="{{ $session->uuid }}"
                                                data-last-message-id="{{ $session->latestMessage ? $session->latestMessage->id : 0 }}"
                                                class="session-row">
                                                <td>
                                                    @if ($session->status === 'active')
                                                        <input type="checkbox" class="form-check-input session-checkbox"
                                                            name="session_uuids[]" value="{{ $session->uuid }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            <span
                                                                class="avatar-title" style="background-color:unset">
                                                                <img src="{{asset('images/users/default.jpg')}}" alt="" width="45" height="45">
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">
                                                                @if ($session->user)
                                                                    {{ $session->user->fullname }}
                                                                @else
                                                                    {{ $session->guest_name }}
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted">
                                                                @if ($session->user)
                                                                    {{ $session->user->email }}
                                                                @else
                                                                    {{ $session->guest_email }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="latest-message">
                                                        @if ($session->latestMessage)
                                                            <p class="mb-0 text-truncate" style="max-width: 300px;">
                                                                <strong>{{ $session->latestMessage->sender_type === 'admin' ? 'Bạn' : 'User' }}:</strong>
                                                                {{ $session->latestMessage->message }}
                                                            </p>
                                                            @if ($session->latestMessage->sender_type === 'user' && !$session->latestMessage->is_read)
                                                                <span class="badge bg-danger badge-sm">Mới</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Chưa có tin nhắn</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($session->status === 'active')
                                                        <span class="badge bg-success">Đang hoạt động</span>
                                                    @else
                                                        <span class="badge bg-secondary">Đã đóng</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $session->last_message_at ? $session->last_message_at->diffForHumans() : $session->created_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.chat.show', $session->uuid) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="no-sessions-row">
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="ri-message-3-line fs-1"></i>
                                                    <p class="mt-2">Chưa có phiên chat nào</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $sessions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .session-row {
            transition: background-color 0.3s ease;
        }

        .session-row:hover {
            background-color: #f8f9fa;
        }

        /* Chỉ highlight khi có tin nhắn mới */
        .session-row.has-new-message {
            animation: highlightNew 1.5s ease-out;
        }

        @keyframes highlightNew {
            0% {
                background-color: #fff3cd;
                transform: scale(1);
            }

            50% {
                background-color: #ffe69c;
                transform: scale(1.01);
            }

            100% {
                background-color: transparent;
                transform: scale(1);
            }
        }

        /* Animation cho session mới */
        .session-row.new-session {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        #last-update-time {
            font-weight: 600;
            color: #667eea;
        }

        /* Pulse effect cho unread badge khi có tin nhắn mới */
        #unread-badge.has-new {
            animation: pulseBadge 1s ease-out;
        }

        @keyframes pulseBadge {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            let pollingInterval = null;
            let currentStatus = 'all';
            let currentPage = {{ $sessions->currentPage() }};
            let lastSessionsState = {}; // Store last state of each session

            // Initialize last state
            initializeLastState();

            // Start polling
            startPolling();

            // Status filter
            $('.status-filter').on('click', function() {
                $('.status-filter').removeClass('active');
                $(this).addClass('active');
                currentStatus = $(this).data('status');
                loadSessions();
            });

            // Refresh button
            $('#refresh-list').on('click', function() {
                loadSessions();
            });

            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.session-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkCloseButton();
            });

            // Individual checkbox
            $(document).on('change', '.session-checkbox', function() {
                updateBulkCloseButton();
            });

            // Update bulk close button state
            function updateBulkCloseButton() {
                const checkedCount = $('.session-checkbox:checked').length;
                $('#bulk-close-btn').prop('disabled', checkedCount === 0);
            }

            // Bulk close form
            $('#bulk-close-form').on('submit', function(e) {
                const checkedCount = $('.session-checkbox:checked').length;
                if (checkedCount === 0) {
                    e.preventDefault();
                    return false;
                }

                if (!confirm(`Bạn có chắc muốn đóng ${checkedCount} phiên chat đã chọn?`)) {
                    e.preventDefault();
                    return false;
                }

                // Collect checked UUIDs
                const uuids = [];
                $('.session-checkbox:checked').each(function() {
                    uuids.push($(this).val());
                });

                // Add hidden inputs
                $(this).find('input[name="session_uuids[]"]').remove();
                uuids.forEach(function(uuid) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'session_uuids[]',
                        value: uuid
                    }).appendTo('#bulk-close-form');
                });
            });

            // Initialize last state from current DOM
            function initializeLastState() {
                $('.session-row').each(function() {
                    const uuid = $(this).data('session-id');
                    const lastMessageId = $(this).data('last-message-id');
                    lastSessionsState[uuid] = {
                        lastMessageId: lastMessageId,
                        exists: true
                    };
                });

            }

            // Start polling for updates
            function startPolling() {

                pollingInterval = setInterval(function() {
                    loadSessions(true); // silent = true
                }, 5000); // Poll every 5 seconds


            }

            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // Load sessions via AJAX
            function loadSessions(silent = false) {
                if (!silent) {}

                $.ajax({
                    url: '{{ route('admin.chat.index') }}',
                    method: 'GET',
                    data: {
                        ajax: true,
                        status: currentStatus,
                        page: currentPage
                    },
                    success: function(response) {
                        if (!silent) {}
                        updateSessionsList(response);
                        updateLastUpdateTime();
                    },
                    error: function(xhr) {
                        console.error('❌ Error loading sessions:', xhr);
                    }
                });
            }

            // Update sessions list - CHỈ HIGHLIGHT KHI CÓ TIN NHẮN MỚI
            function updateSessionsList(data) {
                const tbody = $('#sessions-tbody');
                const currentSessions = {};
                let hasNewMessages = false;

                // Store current sessions
                tbody.find('.session-row').each(function() {
                    const uuid = $(this).data('session-id');
                    currentSessions[uuid] = $(this);
                });

                // Update or add sessions
                if (data.sessions && data.sessions.length > 0) {
                    $('#no-sessions-row').remove();

                    data.sessions.forEach(function(session) {
                        const existingRow = currentSessions[session.uuid];
                        const lastState = lastSessionsState[session.uuid];

                        if (existingRow) {
                            // Check if there's a NEW message
                            const newMessageId = session.latest_message ? session.latest_message.id : 0;
                            const oldMessageId = lastState ? lastState.lastMessageId : 0;

                            if (newMessageId > oldMessageId) {
                                // CÓ TIN NHẮN MỚI - HIGHLIGHT!
                                updateSessionRow(existingRow, session, true);
                                hasNewMessages = true;

                                // Play sound notification (optional)
                                playNotificationSound();
                            } else {
                                // Không có tin nhắn mới - update bình thường
                                updateSessionRow(existingRow, session, false);
                            }

                            // Update last state
                            lastSessionsState[session.uuid] = {
                                lastMessageId: newMessageId,
                                exists: true
                            };
                        } else {
                            // Session mới hoàn toàn
                            const newRow = createSessionRow(session);
                            tbody.prepend(newRow);
                            newRow.addClass('new-session');

                            lastSessionsState[session.uuid] = {
                                lastMessageId: session.latest_message ? session.latest_message.id : 0,
                                exists: true
                            };
                        }

                        delete currentSessions[session.uuid];
                    });

                    // Remove sessions that no longer exist
                    Object.values(currentSessions).forEach(function(row) {
                        const uuid = row.data('session-id');
                        delete lastSessionsState[uuid];
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                } else {
                    // No sessions
                    tbody.html(`
                <tr id="no-sessions-row">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="ri-message-3-line fs-1"></i>
                        <p class="mt-2">Chưa có phiên chat nào</p>
                    </td>
                </tr>
            `);
                    lastSessionsState = {};
                }

                // Update counters
                const oldUnread = parseInt($('#unread-badge').text()) || 0;
                const newUnread = data.unread_count || 0;

                $('#total-sessions').text(data.total || 0);
                $('#unread-badge').text(newUnread);

                if (newUnread > 0) {
                    $('#unread-badge').show();
                    // Nếu số unread tăng lên - thêm animation
                    if (newUnread > oldUnread) {
                        $('#unread-badge').addClass('has-new');
                        setTimeout(function() {
                            $('#unread-badge').removeClass('has-new');
                        }, 1000);
                    }
                } else {
                    $('#unread-badge').hide();
                }
            }

            // Update existing session row
            function updateSessionRow(row, session, hasNewMessage = false) {
                // Update data attribute
                const newMessageId = session.latest_message ? session.latest_message.id : 0;
                row.attr('data-last-message-id', newMessageId);

                // Update latest message
                const latestMessageEl = row.find('.latest-message');
                if (session.latest_message) {
                    const badge = session.latest_message.sender_type === 'user' && !session.latest_message.is_read ?
                        '<span class="badge bg-danger badge-sm">Mới</span>' : '';

                    latestMessageEl.html(`
                <p class="mb-0 text-truncate" style="max-width: 300px;">
                    <strong>${session.latest_message.sender_type === 'admin' ? 'Bạn' : 'User'}:</strong>
                    ${escapeHtml(session.latest_message.message)}
                </p>
                ${badge}
            `);
                } else {
                    latestMessageEl.html('<span class="text-muted">Chưa có tin nhắn</span>');
                }

                // CHỈ HIGHLIGHT NẾU CÓ TIN NHẮN MỚI
                if (hasNewMessage) {
                    row.addClass('has-new-message');
                    setTimeout(function() {
                        row.removeClass('has-new-message');
                    }, 1500);
                }

                // Update time
                row.find('td:nth-child(5)').html(`
            <small class="text-muted">${session.last_message_at_human}</small>
        `);

                // Update status
                const statusBadge = session.status === 'active' ?
                    '<span class="badge bg-success">Đang hoạt động</span>' :
                    '<span class="badge bg-secondary">Đã đóng</span>';
                row.find('td:nth-child(4)').html(statusBadge);
            }

            // Create new session row
            function createSessionRow(session) {
                const userName = session.user ? session.user.fullname : session.guest_name;
                const userEmail = session.user ? session.user.email : session.guest_email;
                const latestMessage = session.latest_message ?
                    `<p class="mb-0 text-truncate" style="max-width: 300px;">
                <strong>${session.latest_message.sender_type === 'admin' ? 'Bạn' : 'User'}:</strong>
                ${escapeHtml(session.latest_message.message)}
            </p>
            ${session.latest_message.sender_type === 'user' && !session.latest_message.is_read ? '<span class="badge bg-danger badge-sm">Mới</span>' : ''}` :
                    '<span class="text-muted">Chưa có tin nhắn</span>';

                const statusBadge = session.status === 'active' ?
                    '<span class="badge bg-success">Đang hoạt động</span>' :
                    '<span class="badge bg-secondary">Đã đóng</span>';

                const checkbox = session.status === 'active' ?
                    `<input type="checkbox" class="form-check-input session-checkbox" name="session_uuids[]" value="${session.uuid}">` :
                    '';

                const messageId = session.latest_message ? session.latest_message.id : 0;

                return $(`
            <tr data-session-id="${session.uuid}" data-last-message-id="${messageId}" class="session-row">
                <td>${checkbox}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="align-middle">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </span>
                        </div>
                        <div>
                            <h6 class="mb-0">${escapeHtml(userName)}</h6>
                            <small class="text-muted">${escapeHtml(userEmail)}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="latest-message">
                        ${latestMessage}
                    </div>
                </td>
                <td>${statusBadge}</td>
                <td>
                    <small class="text-muted">${session.last_message_at_human}</small>
                </td>
                <td>
                    <a href="/admin/chat/${session.uuid}" class="btn btn-sm btn-primary">
                        <i class="ri-eye-line"></i>
                    </a>
                </td>
            </tr>
        `);
            }

            // Play notification sound (optional)
            function playNotificationSound() {
                // Uncomment nếu muốn có âm thanh thông báo
                // const audio = new Audio('/sounds/notification.mp3');
                // audio.play().catch(e => console.log('Cannot play sound:', e));
            }

            // Update last update time
            function updateLastUpdateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                $('#last-update-time').text(timeString);
            }

            // Escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            // Stop polling on page unload
            $(window).on('beforeunload', function() {
                stopPolling();
            });
        });
    </script>
@endsection
