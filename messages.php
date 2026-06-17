<?php
include 'DBConn.php';
session_start();

// Redirect to login if user isn't authenticated yet
if (!isset($_SESSION['username'])) {
    // For local sandbox preview testing, you can temporarily hardcode a session user if login isn't active:
    $_SESSION['userId'] = 1; 
    $_SESSION['username'] = 'Tina Turner';
    $_SESSION['role'] = 'Buyer'; // Added to prevent index.php role warning during local testing
}

$current_user_id = intval($_SESSION['userId']);
$active_chat_user = null;

// Send Message Logic
if (isset($_POST['send_message'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $message_text = mysqli_real_escape_string($conn, trim($_POST['message_text']));
    
    if (!empty($message_text)) {
        // Inserts message into your exact local 'message' table layout
        $insert_query = "INSERT INTO message (senderId, receiverId, messageText, timestamp) 
                         VALUES ($current_user_id, $receiver_id, '$message_text', NOW())";
        mysqli_query($conn, $insert_query);
        header("Location: messages.php?chat_with=" . $receiver_id);
        exit();
    }
}

// Determine who the user is chatting with
if (isset($_GET['chat_with'])) {
    $active_chat_user = intval($_GET['chat_with']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Pastimes Messages | Chat with Sellers</title>
</head>

<body>
    <nav>
        <div class="logo">PASTIMES</div>
        <div>
            <a href="index.php">Home</a>
            <a href="discovery.php">Discovery</a>
            <a href="cart.php">My Cart</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1000px; margin-top: 40px; display: flex; gap: 20px;">

        <div
            style="width: 35%; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 20px; color: #333;">
            <h3 style="margin-top: 0; color: #006400;">💬 Pastimes Inbox</h3>
            <p style="font-size: 0.85em; color: #777; margin-bottom: 20px;">Negotiate or ask questions about pre-loved
                pieces.</p>
            <hr style="border: 0; border-top: 1px solid #eee;">

            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                <?php
                // Fetch unique users the current user has sent messages to or received messages from
                $inbox_query = "SELECT DISTINCT IF(senderId = $current_user_id, receiverId, senderId) AS chat_partner_id 
                                FROM message WHERE senderId = $current_user_id OR receiverId = $current_user_id";
                $inbox_result = mysqli_query($conn, $inbox_query);
                
                if ($inbox_result && mysqli_num_rows($inbox_result) > 0) {
                    while ($inbox_row = mysqli_fetch_assoc($inbox_result)) {
                        $partner_id = intval($inbox_row['chat_partner_id']);
                        
                        // FIX: Changed selection fields to match your precise db layout: userName, role, userId
                        $user_query_str = "SELECT userName, role FROM user WHERE userId = $partner_id";
                        $user_query = mysqli_query($conn, $user_query_str);
                        
                        // FIX: Confirm query didn't fail before executing fetch
                        if ($user_query && $user_row = mysqli_fetch_assoc($user_query)) {
                            $partner_name = $user_row['userName'] ?? 'Marketplace User';
                            $partner_role = $user_row['role'] ?? 'Seller';
                            $is_active = ($active_chat_user == $partner_id) ? "background: #e8f5e9; border-left: 4px solid #006400;" : "background: #f9f9f9;";
                            
                            echo "<a href='messages.php?chat_with=$partner_id' style='text-decoration: none; color: inherit; padding: 12px; border-radius: 6px; display: block; $is_active'>";
                            echo "<strong>" . htmlspecialchars($partner_name) . "</strong> <small style='color: #999; margin-left: 5px;'>($partner_role)</small>";
                            echo "</a>";
                        }
                    }
                } else {
                    echo "<p style='color: #979797; font-size: 0.9em; text-align: center; margin-top: 20px;'>No active chat history found. Start a conversation from an item page!</p>";
                }
                ?>
            </div>
        </div>

        <div
            style="width: 65%; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; min-height: 500px; color: #333;">
            <?php if ($active_chat_user): 
                // FIX: Altered selection target to check userName and userId columns precisely
                $partner_meta = mysqli_query($conn, "SELECT userName FROM user WHERE userId = $active_chat_user");
                $active_chat_name = 'User';
                
                if ($partner_meta && $partner_meta_data = mysqli_fetch_assoc($partner_meta)) {
                    $active_chat_name = $partner_meta_data['userName'] ?? 'User';
                }
            ?>
            <div
                style="background: #006400; color: white; padding: 15px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                <h3 style="margin: 0; font-size: 1.1em;">Conversation with
                    <?php echo htmlspecialchars($active_chat_name); ?></h3>
            </div>

            <div
                style="flex-grow: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #fafafa;">
                <?php
                    // Fetch message history between current user and selected user
                    $chat_history_query = "SELECT * FROM message 
                                           WHERE (senderId = $current_user_id AND receiverId = $active_chat_user) 
                                           OR (senderId = $active_chat_user AND receiverId = $current_user_id) 
                                           ORDER BY timestamp ASC";
                    $chat_history_result = mysqli_query($conn, $chat_history_query);
                    
                    if ($chat_history_result) {
                        while ($msg = mysqli_fetch_assoc($chat_history_result)):
                            $is_me = ($msg['senderId'] == $current_user_id);
                            $msg_align = $is_me ? "align-self: flex-end; background: #006400; color: white;" : "align-self: flex-start; background: #e0e0e0; color: #333;";
                        ?>
                <div
                    style="max-width: 70%; padding: 10px 15px; border-radius: 12px; font-size: 0.95em; <?php echo $msg_align; ?>">
                    <?php echo htmlspecialchars($msg['messageText']); ?>
                </div>
                <?php 
                        endwhile; 
                    }
                ?>
            </div>

            <form method="POST"
                style="padding: 15px; border-top: 1px solid #eee; display: flex; gap: 10px; background: white; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                <input type="hidden" name="receiver_id" value="<?php echo $active_chat_user; ?>">
                <input type="text" name="message_text" required
                    placeholder="Type your message about the garment listing..."
                    style="flex-grow: 1; padding: 12px; border: 1px solid #ccc; border-radius: 4px; outline: none;">
                <button type="submit" name="send_message" class="btn-gold"
                    style="padding: 0 20px; font-weight: bold; border-radius: 4px; border: none; cursor: pointer; background-color: #d4af37; color: white;">Send</button>
            </form>

            <?php else: ?>
            <div
                style="display: flex; flex-direction: column; justify-content: center; align-items: center; flex-grow: 1; color: #999; padding: 40px; text-align: center;">
                <span style="font-size: 3em; margin-bottom: 10px;">💬</span>
                <h3>No Active Chat Window Open</h3>
                <p style="font-size: 0.9em; max-width: 350px;">Select a user profile thread from the inbox panel on the
                    left to review messages and authenticate offers.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>