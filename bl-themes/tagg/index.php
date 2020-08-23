<?php defined('BLUDIT') or die('Bludit CMS.'); ?>
<!DOCTYPE html>
<html lang="<?php echo Theme::lang() ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="generator" content="Gris-CMS">
	<?php
		echo Theme::metaTags('title');
		echo Theme::metaTags('description');
		echo Theme::favicon('vendors/tagg/favicon.png');

		echo Theme::cssBootstrap();
		echo Theme::css('vendors/highlight/style.min.css');
		echo Theme::css('vendors/tagg/main.css');
		echo Theme::css('vendors/tagg/icons.css');

		Theme::plugins('siteHead');
	?>
</head>
<body>
	<!--
		Plugins configured before the body start
	-->
	<?php Theme::plugins('siteBodyBegin') ?>

	<div class="container">

		<!--
			Head with site title and social networks
		-->
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="d-flex flex-column flex-md-row align-items-center p-4 px-md-4 border-bottom">
					<h5 class="my-0 mr-md-auto font-weight-normal"><a href="<?php echo $site->url() ?>"><?php echo $site->title() ?></a></h5>
					<nav class="my-2 my-md-0 mr-md-3">
						<!-- Social Networks -->
						<?php foreach (Theme::socialNetworks() as $key=>$label): ?>
							<a class="p-2" href="<?php echo $site->{$key}(); ?>" target="_blank"><?php echo $label ?></a>
						<?php endforeach; ?>
					</nav>
				</div>
			</div>
		</div>

		<div class="row">

			<!--
				Left sidebar with tags
			-->
			<div class="col-md-3 order-md-1">
				<ul class="tags">
				<?php
					foreach ($tags->db as $key=>$fields) {
						$active = '';
						if ($WHERE_AM_I=='page') {
							foreach ($page->tags(true) as $tagKey) {
								$active = ($key==$tagKey)?'tag-active':'';
								if ($active) {
									break;
								}
							}
						} elseif ($WHERE_AM_I=='tag') {
							$active = ($url->slug()==$key)?'tag-active':'';
						}
						// Show the tag in the sidebar only if contains some page published
						$showTag = false;
						foreach ($fields['list'] as $pageKey) {
							if ($pages->db[$pageKey]['type']!='draft') {
								$showTag = true;
								break;
							}
						}
						if ($showTag) {
							$html  = '<li class="tags">';
							$html .= '<a class="tag '.$active.' icon-tag" href="'.DOMAIN_TAGS.$key.'">';
							$html .= $fields['name'];
							$html .= '</a>';
							$html .= '</li>';
							echo $html;

						}
					}
				?>
				</ul>
			</div>

			<!--
				Main content
			-->
			<div class="col-md-8 order-md-2 mb-4">
			<?php
				if ($WHERE_AM_I=='page') {
					include(__DIR__.'/templates/page.php');
				} else {
					include(__DIR__.'/templates/home.php');
				}
			?>
			</div>

		</div>

		<!--
			Footer
		-->
		<footer class="my-5 pt-5 text-muted text-center text-small">
			<?php if ($WHERE_AM_I=='home'): ?>
			<h1><?php echo $site->description() ?></h1>
			<?php else: ?>
			<div><?php echo $site->description() ?></div>
			<?php endif; ?>
			<p class="mb-1"><?php echo $site->footer() ?>. Powered by <a href="https://github.com/dignajar/gris" target="_blank">Gris CMS</a></p>
		</footer>
	</div>

	<!--
		Javascript libraries
	-->
	<?php
		echo Theme::js('vendors/highlight/highlight.min.js');
	?>
	<script>hljs.initHighlightingOnLoad();</script>

	<!--
		Plugins configured before the body end
	-->
	<?php Theme::plugins('siteBodyEnd') ?>
</body>
</html>