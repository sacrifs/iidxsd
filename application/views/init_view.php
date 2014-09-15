<!-- CONTENT START -->
<body class="top">
	<header>
		<h1><img src="images/title.png" title="beatmaniaIIDX全曲表ver.F IIDXSmartData" alt="beatmaniaIIDX全曲表ver.F IIDXSmartData" width="247" height="58" /></h1>
		<form id="searchForm" name="searchForm">
			<input type="text" id="searchWord" name="searchWord" />
			<input type="button" onClick="IIDXSD.searchData()" value="検索" />
		</form>
	</header>
	<article>
		<nav id="quickmenu">
			<section id="versionAC">
				<h1 class="container ac">AC</h1>
				<section class="container latest">
					<h2><?php echo $VERSION_NAME_LIST[$AC_VER_MAX] ?></h2>
					<ul class="sublink">
						<li><a href="view/ac/<?php echo $AC_VER_MAX ?>/new">new</a></li>
						<li><a href="view/ac/<?php echo $AC_VER_MAX ?>/all">all</a></li>
						<li><a href="view/ac/<?php echo $AC_VER_MAX ?>/delete">delete</a></li>
						<li><a href="view/ac/<?php echo $AC_VER_MAX ?>/revival">revival</a></li>
					</ul>
				</section>
<?php for ($i=$AC_VER_MIN; $i < $AC_VER_MAX; $i++): ?>
				<section class="container">
					<h2><?php echo $VERSION_NAME_LIST[$i] ?></h2>
					<ul class="sublink">
						<?php if($i != 0) echo '<li><a href="view/ac/'.$i.'/new">new</a></li>'."\n" ?>
						<li><a href="view/ac/<?php echo $i ?>/all">all</a></li>
						<?php if($i >= 3 && $i != 9) echo '<li><a href="view/ac/'.$i.'/delete">delete</a></li>'."\n" ?>
						<?php if($i >= 9) echo '<li><a href="view/ac/'.$i.'/revival">revival</a></li>'."\n" ?>
					</ul>
				</section>
<?php endfor ?>
			</section>
			<section id="versionCS">
				<h1 class="container cs">CS</h1>
				<section class="container latest">
					<h2><?php echo $VERSION_NAME_LIST[$CS_VER_MAX] ?></h2>
					<ul class="sublink">
						<li><a href="view/cs/<?php echo $CS_VER_MAX ?>/new">new</a></li>
						<li><a href="view/cs/<?php echo $CS_VER_MAX ?>/all">all</a></li>
					</ul>
				</section>
<?php for ($i=$CS_VER_MIN; $i < $CS_VER_MAX; $i++): ?>
				<section class="container">
					<h2><?php echo $VERSION_NAME_LIST[$i] ?></h2>
					<ul class="sublink">
						<li><a href="view/cs/<?php echo $i ?>/new">new</a></li>
						<li><a href="view/cs/<?php echo $i ?>/all">all</a></li>
					</ul>
				</section>
<?php endfor ?>
			</section>
			<section id="versionALL">
				<h1 class="container all"><a href="view/all">ALL</a></h1>
			</section>
			<section id="menuInfo">
				<ul>
					<li class="container"><a href="about">ABOUT</a></li>
					<li class="container"><a href="howtouse">HOW TO USE</a></li>
					<li class="container"><a href="link">LINK</a></li>
					<li class="container"><a href="https://twitter.com/iidxsd_bot" target="_blank">Twitter</a></li>
					<li class="container"><img id="barcode" src="images/barcode.png" alt="バーコード" width="148" height="148"></li>
				</ul>
			</section>
		</nav>
	</article>
<!-- CONTENT END -->
