<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<div id="settingsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="settingsModal"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Settings</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="settingsForm">

					<div class="form-group row">
						<label for="jstitle" class="col-sm-2 col-form-label">Site title</label>
						<div class="col-sm-10">
							<input name="title" id="jstitle" class="form-control" value="<?php echo $site->title() ?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="staticEmail" class="col-sm-2 col-form-label">Theme</label>
						<div class="col-sm-10">
							<select name="theme" id="jstheme" class="custom-select mr-sm-2">
								<?php
									$themes = buildThemes();
									foreach ($themes as $theme):
								?>
								<option value="<?php echo $theme['dirname'] ?>" <?php echo ($theme['dirname']==$site->theme()?'selected':'') ?>><?php echo $theme['name'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
<script>
var _settingsTimer = null; // Timer object for the autosave settings
var _settingsTimeout = 2.5; // Second before call the autosave settings
$(document).ready(function() {
	$("#settingsForm").on("change keyup paste", ":input", function(e) {
		console.log("Settings changes");

		// Reset timer
		if (_settingsTimer!=null) {
			clearTimeout(_settingsTimer);
		}

		// Activate timer
		_settingsTimer = setTimeout(function() {
			var form = {};
			$.each($("#settingsForm").serializeArray(), function() {
				form[this.name] = this.value;
			});
			Ajax.updateSettings(form);
		}, _settingsTimeout*1000);
	});
});
</script>