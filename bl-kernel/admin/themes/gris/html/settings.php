<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<div id="settingsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="settingsModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<form id="settingsForm">

					<ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
						<li class="nav-item">
							<a id="site-tab" href="#site" aria-controls="site" class="nav-link active" data-toggle="tab" role="tab" aria-selected="true">Site</a>
						</li>
						<li class="nav-item">
							<a id="theme-tab" href="#theme" aria-controls="theme" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">Theme</a>
						</li>
						<li class="nav-item">
							<a id="seo-tab" href="#seo" aria-controls="seo" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">SEO</a>
						</li>
					</ul>
					<div class="tab-content" id="settingsTabContent">

						<!-- site -->
						<div id="site" aria-labelledby="site-tab" class="tab-pane show active" role="tabpanel">
							<!-- Site title -->
							<div class="form-group row">
								<label for="jstitle" class="col-sm-2 col-form-label">Title</label>
								<div class="col-sm-10">
									<input name="title" id="jstitle" class="form-control" value="<?php echo $site->title() ?>">
								</div>
							</div>

							<!-- Site slogan -->
							<div class="form-group row">
								<label for="jsslogan" class="col-sm-2 col-form-label">Slogan</label>
								<div class="col-sm-10">
									<input name="slogan" id="jsslogan" class="form-control" value="<?php echo $site->slogan() ?>">
								</div>
							</div>

							<!-- Site description -->
							<div class="form-group row">
								<label for="jsdescription" class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10">
									<input name="description" id="jsdescription" class="form-control" value="<?php echo $site->description() ?>">
								</div>
							</div>
						</div>

						<!-- Theme -->
						<div id="theme" aria-labelledby="theme-tab" class="tab-pane" role="tabpanel" >
							<!-- Theme selection -->
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
						</div>

						<!-- SEO -->
						<div id="seo" aria-labelledby="seo-tab" class="tab-pane" role="tabpanel">
							<!-- Title format homepage -->
							<div class="form-group row">
								<label for="jstitleFormatHomepage" class="col-sm-2 col-form-label">Homepage</label>
								<div class="col-sm-10">
									<input name="titleFormatHomepage" id="jstitleFormatHomepage" class="form-control" value="<?php echo $site->titleFormatHomepage() ?>">
								</div>
							</div>

							<!-- Title format pages -->
							<div class="form-group row">
								<label for="jstitleFormatPages" class="col-sm-2 col-form-label">Pages</label>
								<div class="col-sm-10">
									<input name="titleFormatPages" id="jstitleFormatPages" class="form-control" value="<?php echo $site->titleFormatPages() ?>">
								</div>
							</div>

							<!-- Title format category -->
							<div class="form-group row">
								<label for="jstitleFormatCategory" class="col-sm-2 col-form-label">Category</label>
								<div class="col-sm-10">
									<input name="titleFormatCategory" id="jstitleFormatCategory" class="form-control" value="<?php echo $site->titleFormatCategory() ?>">
								</div>
							</div>
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