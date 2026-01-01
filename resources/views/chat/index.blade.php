@extends('learning.index')

@section('course')
    <style>
        .chat-message {
            max-width: 70%;
        }

        .user-item.active {
            background-color: #0d6efd !important;
            color: white;
        }

        .user-item img {
            object-fit: cover;
        }

        #chat-box {
            height: 420px;
            overflow-y: auto;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .bubble {
            padding: 10px 15px;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            max-width: 75%;
        }

        .bubble-left {
            background: white;
        }

        .bubble-right {
            background: #0d6efd;
            color: white;
        }

        .typing-indicator {
            font-style: italic;
            color: #888;
            font-size: 0.85rem;
            padding-left: 50px;
        }
    </style>

    <div class="container py-3">
        <div class="row">
            <!-- Sidebar Users -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">👥 Pengguna</div>
                    <div class="list-group list-group-flush" id="user-list">
                        <button
                            class="list-group-item list-group-item-action d-flex align-items-center gap-2 user-item active"
                            onclick="selectUser(null, this)">
                            <span>🌐 Semua</span>
                        </button>
                        @foreach ($users as $user)
                            <button class="list-group-item list-group-item-action d-flex align-items-center gap-2 user-item"
                                onclick="selectUser({{ $user->id }}, this)">
                                <img src="{{ $user->photo
                                    ? asset('storage/' . $user->photo)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->name) .
                                        '&background=random&color=fff&rounded=true&size=160' }}"
                                    class="rounded-circle" width="32" height="32" alt="User">
                                <span>{{ $user->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">💬 Chat Pengguna</div>
                    <div class="card-body" id="chat-box">
                        <!-- Chat content via JS -->
                    </div>
                    <div id="typing-status" class="px-3 pb-2" style="display: none;">
                        <div class="typing-indicator">✍️ Pengguna sedang mengetik...</div>
                    </div>
                    <div class="card-footer bg-white">
                        <form id="chat-form">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $courseId }}">
                            <input type="hidden" id="receiver_id" name="receiver_id">
                            <div class="input-group">
                                <input type="text" id="message" name="message" class="form-control"
                                    placeholder="Tulis pesan..." autocomplete="off">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let selectedUserId = null;
        let typingTimer;

        function selectUser(userId, btn = null) {
            selectedUserId = userId;
            $('#receiver_id').val(userId);

            $('.user-item').removeClass('active');
            if (btn) $(btn).addClass('active');

            loadMessages();
        }

        function loadMessages() {
            let courseId = "{{ $courseId }}";
            $.get(`/chat/${courseId}/messages/${selectedUserId ?? ''}`, function(data) {
                $('#chat-box').html('');
                data.forEach(function(msg) {
                    let isMe = msg.sender_id === {{ Auth::id() }};
                    let html = `
                <div class="d-flex ${isMe ? 'justify-content-end' : 'justify-content-start'}">
                    <div class="d-flex ${isMe ? 'flex-row-reverse' : ''} align-items-start chat-message gap-2">
                        <img src="${msg.sender.photo 
    ? `/storage/${msg.sender.photo}` 
    : `https://ui-avatars.com/api/?name=${encodeURIComponent(msg.sender.name)}&background=random&color=fff&rounded=true&size=160`}"
                            class="rounded-circle border" width="36" height="36" alt="User">
                        <div class="bubble ${isMe ? 'bubble-right' : 'bubble-left'}">
                            <div class="fw-semibold small mb-1">${msg.sender.name}</div>
                            <div>${msg.message}</div>
                        </div>
                    </div>
                </div>`;
                    $('#chat-box').append(html);
                });
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
        }

        $('#chat-form').submit(function(e) {
            e.preventDefault();
            $.post("{{ route('chat.send') }}", $(this).serialize(), function() {
                $('#message').val('');
                $('#typing-status').fadeOut();
                loadMessages();
            });
        });

        // Kirim sinyal sedang mengetik
        $('#message').on('input', function() {
            clearTimeout(typingTimer);

            $.post("{{ route('chat.typing') }}", {
                _token: '{{ csrf_token() }}',
                course_id: '{{ $courseId }}',
                receiver_id: selectedUserId
            });

            typingTimer = setTimeout(() => {
                // Tidak perlu aksi khusus saat berhenti mengetik
            }, 1000);
        });

        // Cek status sedang mengetik lawan bicara
        function checkTypingStatus() {
            let courseId = "{{ $courseId }}";
            let receiverId = selectedUserId ?? '';

            $.get(`/chat/${courseId}/typing-status/${receiverId}`, function(data) {
                if (data.typing) {
                    $('#typing-status').fadeIn();
                } else {
                    $('#typing-status').fadeOut();
                }
            });
        }

        // Loop auto refresh
        setInterval(() => {
            loadMessages();
            checkTypingStatus();
        }, 3000);

        $(document).ready(() => {
            selectUser(null, $('.user-item').first()[0]);
        });
    </script>
@endsection
