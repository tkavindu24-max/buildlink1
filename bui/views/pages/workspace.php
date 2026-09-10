<?php requireLogin(); 
    $pId = (int)($_GET["id"] ?? 0); global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?"); $stmt->execute([$pId]); $project = $stmt->fetch();
    
    // Authorization Check
    $isOwner = ($project && $project['user_id'] == $_SESSION['user_id']);
    $isLinkedProf = false;
    if ($_SESSION['role'] === 'professional') {
        $chk = $pdo->prepare("SELECT id FROM project_professionals WHERE project_id=? AND professional_id=? AND status IN ('accepted', 'verified')");
        $chk->execute([$pId, $_SESSION['user_id']]);
        if ($chk->fetch()) $isLinkedProf = true;
    }
    if (!$isOwner && !$isLinkedProf && $_SESSION['role'] !== 'admin') {
        $_SESSION['error'] = "You must accept the project request to enter the workspace.";
        redirect("index.php?page=professional-projects");
    }
?>
<section class="section">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 8px; flex-wrap:wrap; gap:16px;">
            <h2 style="font-size:32px;">☁️ Project Workspace</h2>
            <?php if($isOwner || $isLinkedProf): ?> <a href="index.php?page=project-details&id=<?= $pId ?>" class="btn btn-outline">Back to project</a> <?php endif; ?>
        </div>
        <p style="color:var(--text-muted); margin-bottom:32px; font-size:16px;"><strong>Active Environment:</strong> <?= e($project["title"]) ?> — files and messages are shared securely with project members.</p>

        <!-- Chat / Files Feed -->
        <div class="workspace-feed" id="wsFeed">
            <?php 
            $ws = $pdo->prepare("SELECT pw.*, u.name, u.role FROM project_workspace pw JOIN users u ON pw.user_id = u.id WHERE pw.project_id = ? ORDER BY pw.created_at ASC");
            $ws->execute([$pId]); $messages = $ws->fetchAll();
            if ($messages): foreach ($messages as $msg): 
                $isMe = ($msg['user_id'] == $_SESSION['user_id']);
            ?>
                <div class="ws-msg <?= $isMe ? 'own' : '' ?>">
                    <div class="ws-msg-box">
                        <div class="ws-meta"><?= e($msg['name']) ?> (<?= ucfirst(e($msg['role'])) ?>) &nbsp;•&nbsp; <?= e(date("M j, g:i A", strtotime($msg['created_at']))) ?></div>
                        <?php if(!empty($msg['message'])): ?>
                            <p style="margin-top:8px; font-size:15px; color:inherit;"><?= nl2br(e($msg['message'])) ?></p>
                        <?php endif; ?>
                        <?php if($msg['file_url']): ?>
                            <a href="<?= e($msg['file_url']) ?>" target="_blank" rel="noopener" class="file-attachment">
                                📎 <?= strtoupper(e($msg['file_type'])) ?> Document attached
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div style="display:flex; justify-content:center; align-items:center; height:200px; flex-direction:column;">
                    <div class="icon blue" style="margin-bottom:12px;">☁️</div>
                    <h3 style="margin-bottom:8px;">Workspace is empty</h3>
                    <p style="color:var(--text-muted); font-weight:500;">Start the conversation or upload the first blueprint.</p>
                </div>
            <?php endif; ?>
        </div>
        <script> var feed = document.getElementById("wsFeed"); feed.scrollTop = feed.scrollHeight; </script>

        <!-- Upload Form -->
        <div class="card" style="padding: 32px;">
            <form method="POST" action="index.php?page=workspace&id=<?= $pId ?>" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:16px;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="project_id" value="<?= $pId ?>">
                <input type="hidden" name="post_workspace" value="1">
                
                <textarea name="message" placeholder="Type your architectural feedback, question, or logistics update..." style="width:100%; padding:16px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-color); color:var(--text-main); resize:vertical; min-height:100px; font-family:'Inter',sans-serif; font-size:15px; transition:var(--transition);"></textarea>
                
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; background:var(--bg-color); padding:16px 20px; border-radius:var(--radius-md); border:1px dashed var(--border);">
                    <div>
                        <label style="font-size:13px; font-weight:600; color:var(--text-main); display:block; margin-bottom:8px;">Attach a document (Optional)</label>
                        <input type="file" name="workspace_file" accept=".jpg,.jpeg,.png,.pdf,.zip" style="font-size:14px; color:var(--text-muted);">
                    </div>
                    <button type="submit" class="btn btn-blue" style="padding:12px 28px; font-size:15px;">Send to Workspace</button>
                </div>
            </form>
        </div>
    </div>
</section>