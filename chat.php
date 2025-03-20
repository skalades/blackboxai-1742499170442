<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// Get all users except current user
$users = $db->fetchAll("
    SELECT id, username, full_name, profile_picture, online_status, last_activity 
    FROM users 
    WHERE id != ? AND role = 'user'
    ORDER BY online_status DESC, last_activity DESC
", [$userId]);

// Get selected user for chat
$selectedUserId = isset($_GET['user']) ? (int)$_GET['user'] : null;
$selectedUser = null;

if ($selectedUserId) {
    $selectedUser = $db->fetchOne("
        SELECT id, username, full_name, profile_picture, online_status, last_activity 
        FROM users 
        WHERE id = ?
    ", [$selectedUserId]);
}

// Get recent messages for selected chat
$messages = [];
if ($selectedUser) {
    $messages = $db->fetchAll("
        SELECT m.*, u.full_name, u.profile_picture 
        FROM messages m 
        JOIN users u ON m.sender_id = u.id 
        WHERE (m.sender_id = ? AND m.receiver_id = ?) 
           OR (m.sender_id = ? AND m.receiver_id = ?) 
        ORDER BY m.created_at ASC
    ", [$userId, $selectedUserId, $selectedUserId, $userId]);

    // Mark messages as read
    $db->update('messages', 
        ['is_read' => 1],
        'receiver_id = ? AND sender_id = ? AND is_read = 0',
        ['receiver_id' => $userId, 'sender_id' => $selectedUserId]
    );
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="aero-card">
        <div class="grid grid-cols-1 md:grid-cols-4">
            <!-- Users List -->
            <div class="border-r border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold">Chat Alumni</h2>
                </div>
                <div class="overflow-y-auto h-[600px]">
                    <?php foreach ($users as $user): 
                        $unreadCount = $db->fetchOne("
                            SELECT COUNT(*) as count 
                            FROM messages 
                            WHERE sender_id = ? AND receiver_id = ? AND is_read = 0
                        ", [$user['id'], $userId])['count'];
                        
                        $lastMessage = $db->fetchOne("
                            SELECT message_text, created_at 
                            FROM messages 
                            WHERE (sender_id = ? AND receiver_id = ?) 
                               OR (sender_id = ? AND receiver_id = ?) 
                            ORDER BY created_at DESC 
                            LIMIT 1
                        ", [$userId, $user['id'], $user['id'], $userId]);
                    ?>
                        <a href="?user=<?php echo $user['id']; ?>" 
                           class="block p-4 hover:bg-gray-50 transition-colors duration-150 <?php echo ($selectedUserId == $user['id']) ? 'bg-gray-50' : ''; ?>">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <img src="<?php echo $user['profile_picture'] ?: 'assets/images/default-avatar.png'; ?>" 
                                         alt="<?php echo htmlspecialchars($user['full_name']); ?>" 
                                         class="h-10 w-10 rounded-full object-cover">
                                    <?php if ($user['online_status']): ?>
                                        <span class="absolute bottom-0 right-0 h-3 w-3 bg-green-400 rounded-full border-2 border-white"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo htmlspecialchars($user['full_name']); ?>
                                        </p>
                                        <?php if ($unreadCount > 0): ?>
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary rounded-full">
                                                <?php echo $unreadCount; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($lastMessage): ?>
                                        <p class="text-sm text-gray-500 truncate">
                                            <?php echo htmlspecialchars(substr($lastMessage['message_text'], 0, 30)) . '...'; ?>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <?php echo date('d M H:i', strtotime($lastMessage['created_at'])); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-span-3 flex flex-col h-[600px]">
                <?php if ($selectedUser): ?>
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 flex items-center space-x-3">
                        <div class="relative">
                            <img src="<?php echo $selectedUser['profile_picture'] ?: 'assets/images/default-avatar.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($selectedUser['full_name']); ?>" 
                                 class="h-10 w-10 rounded-full object-cover">
                            <?php if ($selectedUser['online_status']): ?>
                                <span class="absolute bottom-0 right-0 h-3 w-3 bg-green-400 rounded-full border-2 border-white"></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium"><?php echo htmlspecialchars($selectedUser['full_name']); ?></h3>
                            <p class="text-sm text-gray-500">
                                <?php echo $selectedUser['online_status'] ? 'Online' : 'Terakhir dilihat ' . date('d M Y H:i', strtotime($selectedUser['last_activity'])); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <?php foreach ($messages as $message): 
                            $isSender = $message['sender_id'] == $userId;
                        ?>
                            <div class="flex <?php echo $isSender ? 'justify-end' : 'justify-start'; ?>">
                                <div class="flex items-end space-x-2 max-w-[70%]">
                                    <?php if (!$isSender): ?>
                                        <img src="<?php echo $message['profile_picture'] ?: 'assets/images/default-avatar.png'; ?>" 
                                             alt="<?php echo htmlspecialchars($message['full_name']); ?>" 
                                             class="h-8 w-8 rounded-full object-cover">
                                    <?php endif; ?>
                                    
                                    <div class="<?php echo $isSender ? 'bg-primary text-white' : 'bg-gray-100 text-gray-900'; ?> rounded-lg px-4 py-2">
                                        <p class="text-sm"><?php echo nl2br(htmlspecialchars($message['message_text'])); ?></p>
                                        <p class="text-xs <?php echo $isSender ? 'text-white/80' : 'text-gray-500'; ?> mt-1">
                                            <?php echo date('H:i', strtotime($message['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200">
                        <form id="messageForm" class="flex space-x-2">
                            <input type="hidden" name="receiver_id" value="<?php echo $selectedUser['id']; ?>">
                            <textarea name="message" 
                                      class="flex-1 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 resize-none"
                                      rows="1" 
                                      placeholder="Tulis pesan..."
                                      required></textarea>
                            <button type="submit" class="btn-primary px-6">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Pilih alumni untuk memulai chat</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for real-time chat -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages');
    const messageForm = document.getElementById('messageForm');
    
    // Scroll to bottom of messages
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }
    
    // Initial scroll
    scrollToBottom();
    
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(messageForm);
            
            fetch('chat-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear input
                    messageForm.reset();
                    
                    // Add message to chat
                    const messageHtml = `
                        <div class="flex justify-end">
                            <div class="flex items-end space-x-2 max-w-[70%]">
                                <div class="bg-primary text-white rounded-lg px-4 py-2">
                                    <p class="text-sm">${data.message.text}</p>
                                    <p class="text-xs text-white/80 mt-1">${data.message.time}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal mengirim pesan. Silakan coba lagi.', 'error');
            });
        });
    }
    
    // Auto-resize textarea
    const messageInput = document.querySelector('textarea[name="message"]');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // Poll for new messages
    let lastMessageTime = messages.length > 0 ? messages[messages.length - 1].created_at : null;
    
    function pollNewMessages() {
        const receiverId = document.querySelector('input[name="receiver_id"]').value;
        
        fetch(`chat-handler.php?action=poll&receiver_id=${receiverId}&last_time=${lastMessageTime}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        const messageHtml = `
                            <div class="flex justify-start">
                                <div class="flex items-end space-x-2 max-w-[70%]">
                                    <img src="${message.profile_picture || 'assets/images/default-avatar.png'}" 
                                         alt="${message.full_name}" 
                                         class="h-8 w-8 rounded-full object-cover">
                                    <div class="bg-gray-100 text-gray-900 rounded-lg px-4 py-2">
                                        <p class="text-sm">${message.text}</p>
                                        <p class="text-xs text-gray-500 mt-1">${message.time}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    });
                    
                    lastMessageTime = data.messages[data.messages.length - 1].created_at;
                    scrollToBottom();
                }
            })
            .catch(error => console.error('Error polling messages:', error));
    }
    
    if (messagesContainer) {
        // Poll every 3 seconds
        setInterval(pollNewMessages, 3000);
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>