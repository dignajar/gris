<?php defined('BLUDIT') or die('Bludit CMS.');
/*
	Template for a page.
	Variables defined from Bludit:
		- $page (page object for the current page)
*/
?>

<!--
	Plugins configured before the pages
-->
<?php Theme::plugins('pageBegin') ?>

<!--
	Page
-->
<article class="page">
	<a href="<?php echo $page->permalink() ?>">
		<h1 class="page-title m-0"><?php echo $page->title() ?></h1>
	</a>
	<div class="page-date mb-3"><?php echo $page->date() ?> <?php echo ($page->dateModified()?' - <i>Last updated: '.$page->dateModified().'</i>':'')?></div>

	<div class="page-content mb-5">
	<?php echo $page->content() ?>
	</div>
</article>

<!--
	Related posts
-->
<?php
// Get related pages and sort by date
$relatedPages = $page->related();
$relatedPagesSortByDate = array();
foreach ($relatedPages as $pageKey) {
	$tmpPage = new Page($pageKey);
	$relatedPagesSortByDate[$tmpPage->date('U')] = new Page($pageKey);
}

if (!empty($relatedPagesSortByDate)) {
	krsort($relatedPagesSortByDate);
	echo '<h2>Related posts</h2>';
	echo '<ul class="list-group list-group-flush">';
	foreach ($relatedPagesSortByDate as $related) {
			echo '<li class="list-group-item">';
			echo '<a href="'.$related->permalink().'" class="mr-2">'.$related->title().'</a>';
			$relatedTags = $related->tags(true);
			foreach ($relatedTags as $tagKey=>$tagName) {
				echo '<span class="badge badge-primary mr-1 ml-1">'.$tagName.'</span>';
			}
			echo '<div class="page-date">'.$related->date();
			if ($related->dateModified()) {
				echo '<i> - Last updated: '.$related->dateModified().'</i>';
			}
			echo '</div>';
			echo '</li>';
	}
}
?>
</ul>

<!-- <div class="clearfix">
<?php
	if ($page->previousKey()) {
		$previousPage = buildPage($page->previousKey());
		echo '<a class="btn btn-primary float-left" href="'.$previousPage->permalink().'">&larr; '.$previousPage->title().'</a>';
	}

	if ($page->nextKey()) {
		$nextPage = buildPage($page->nextKey());
		echo '<a class="btn btn-primary float-right" href="'.$nextPage->permalink().'">'.$nextPage->title().' &rarr;</a>';
	}
?>
</div> -->

<!--
	Plugins configured after the pages
-->
<?php Theme::plugins('pageEnd') ?>
