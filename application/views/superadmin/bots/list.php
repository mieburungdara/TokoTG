<?php $this->load->view('superadmin/templates/header', ['title' => 'Superadmin - Manage Bots']); ?>

        <h2 class="mb-4 text-center">Manage Telegram Bots</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?php echo site_url('superadmin/add_bot'); ?>" class="btn btn-success">Add New Bot</a>
            <a href="<?php echo site_url('superadmin/dashboard'); ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($bots)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>API Key</th>
                            <th>Webhook URL</th>
                            <th>Webhook Status</th>
                            <th>Mode</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bots as $bot): ?>
                            <tr>
                                <td><?php echo $bot['id']; ?></td>
                                <td><?php echo $bot['username']; ?></td>
                                <td><?php echo substr($bot['api_key'], 0, 10) . '...'; ?></td>
                                <td><?php echo !empty($bot['webhook_url']) ? $bot['webhook_url'] : 'N/A'; ?></td>
                                <td>
                                    <?php
                                        if (!empty($bot['webhook_url'])) {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Inactive</span>';
                                        }
                                    ?>
                                </td>
                                <td><?php echo ucfirst($bot['mode']); ?></td>
                                <td><?php echo $bot['created_at']; ?></td>
                                <td><?php echo $bot['updated_at']; ?></td>
                                <td class="action-buttons">
                                    <a href="<?php echo site_url('superadmin/edit_bot/' . $bot['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="<?php echo site_url('superadmin/delete_bot/' . $bot['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bot?');">Delete</a>
                                    <a href="<?php echo site_url('superadmin/switch_bot_mode/' . $bot['id']); ?>" class="btn btn-sm btn-info">Switch Mode</a>
                                    <?php if ($bot['mode'] == 'webhook'): ?>
                                        <button type="button" class="btn btn-sm btn-warning set-webhook-btn" data-bs-toggle="modal" data-bs-target="#setWebhookModal" data-bot-id="<?php echo $bot['id']; ?>" data-bot-username="<?php echo $bot['username']; ?>">
                                            Set Webhook
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary delete-webhook-btn" data-bs-toggle="modal" data-bs-target="#deleteWebhookModal" data-bot-id="<?php echo $bot['id']; ?>" data-bot-username="<?php echo $bot['username']; ?>">
                                            Delete Webhook
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">No bots configured yet.</p>
        <?php endif; ?>

<!-- Set Webhook Modal -->
<div class="modal fade" id="setWebhookModal" tabindex="-1" aria-labelledby="setWebhookModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="setWebhookModalLabel">Set Webhook Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to set the webhook for bot <strong id="setWebhookBotUsername"></strong>? This will overwrite any existing webhook.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmSetWebhook" href="#" class="btn btn-warning">Set Webhook</a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Webhook Modal -->
<div class="modal fade" id="deleteWebhookModal" tabindex="-1" aria-labelledby="deleteWebhookModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteWebhookModalLabel">Delete Webhook Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the webhook for bot <strong id="deleteWebhookBotUsername"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmDeleteWebhook" href="#" class="btn btn-danger">Delete Webhook</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var setWebhookModal = document.getElementById('setWebhookModal');
    if(setWebhookModal) {
        setWebhookModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var botId = button.getAttribute('data-bot-id');
            var botUsername = button.getAttribute('data-bot-username');
            
            var modalUsername = setWebhookModal.querySelector('#setWebhookBotUsername');
            var confirmButton = setWebhookModal.querySelector('#confirmSetWebhook');
            
            modalUsername.textContent = botUsername;
            confirmButton.href = "<?php echo site_url('superadmin/set_bot_webhook/'); ?>" + botId;
        });
    }

    var deleteWebhookModal = document.getElementById('deleteWebhookModal');
    if(deleteWebhookModal){
        deleteWebhookModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var botId = button.getAttribute('data-bot-id');
            var botUsername = button.getAttribute('data-bot-username');
            
            var modalUsername = deleteWebhookModal.querySelector('#deleteWebhookBotUsername');
            var confirmButton = deleteWebhookModal.querySelector('#confirmDeleteWebhook');
            
            modalUsername.textContent = botUsername;
            confirmButton.href = "<?php echo site_url('superadmin/delete_bot_webhook/'); ?>" + botId;
        });
    }
});
</script>

<?php $this->load->view('superadmin/templates/footer'); ?>
