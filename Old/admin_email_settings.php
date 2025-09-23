<?php
// Fetch current settings from DB
// $settings = ... (fetch from DB)
?>
<form method="post" action="save_email_settings.php">
  <label>SMTP Host: <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host']) ?>"></label>
  <label>SMTP Port: <input type="number" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port']) ?>"></label>
  <label>SMTP Username: <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user']) ?>"></label>
  <label>SMTP Password: <input type="password" name="smtp_pass" value=""></label>
  <label>Use TLS: <input type="checkbox" name="use_tls" <?= $settings['use_tls'] ? 'checked' : '' ?>></label>
  <label>From Email: <input type="email" name="from_email" value="<?= htmlspecialchars($settings['from_email']) ?>"></label>
  <label>From Name: <input type="text" name="from_name" value="<?= htmlspecialchars($settings['from_name']) ?>"></label>
  <button type="submit">Save Settings</button>
  <button type="button" onclick="sendTestEmail()">Send Test Email</button>
</form>
<script>
function sendTestEmail() {
  // AJAX call to trigger test email with current settings
}
</script>