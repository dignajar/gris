<?php defined('BLUDIT') or die('Bludit CMS.');
/*
	Template for the home page.
	Variables defined from Bludit:
		- $content (array with "page objects" defined for the particular page number)
		- Paginator:: (static object with information about the page number, next page, previous page, amout of pages, etc)
*/
?>

<!--
	Print only when there is not content to show.
	For example, when the blog/website is new and there is no content.
-->
<?php if (empty($content)): ?>
<p>There's no content yet.</p>
<?php endif; ?>

<!--
	Print all pages defined in the variable $content
-->
<?php foreach ($content as $page): ?>
	<article class="page">
		<?php Theme::plugins('pageBegin') ?>

		<a href="<?php echo $page->permalink() ?>">
			<h2 class="page-title m-0"><?php echo $page->title() ?></h2>
		</a>
		<div class="page-date mb-3"><?php echo $page->date() ?></div>

		<div class="page-content">
		<?php echo $page->contentBreak() ?>
		</div>

		<?php if ($page->readMore()): ?>
		<a class="read-more" href="<?php echo $page->permalink() ?>">Read more</a>
		<?php endif; ?>

		<?php Theme::plugins('pageEnd') ?>
	</article>
	<hr>
<?php endforeach; ?>

<!--
	Next and previous page only if they are defined
-->
<div class="clearfix">
<?php
	if (Paginator::showNext()) {
		echo '<a class="btn btn-primary float-left" href="'.Paginator::nextPageUrl().'">&larr; '.$language->get('Previous page').'</a>';
	}

	if (Paginator::showPrev()) {
		echo '<a class="btn btn-primary float-right" href="'.Paginator::previousPageUrl().'">'.$language->get('Next page').' &rarr;</a>';
	}
?>
</div>
