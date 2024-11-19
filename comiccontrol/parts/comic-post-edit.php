<?php //comic-post-edit.php - handles editing existing comic posts ?>

<?php //include necessary libraries ?>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js" type="text/javascript" /></script>

<?php

//get the requested post
$thiscomic = $ccpage->module->getPost(getSlug(4));

//create and output quick links
$links = array(
	array(
		'link' => $ccurl . $navslug . '/' . $ccpage->module->slug,
		'text' => str_replace('%s',$ccpage->title,$lang['Return to managing %s'])
	),
	array(
		'link' => $ccurl . $navslug.'/'.$ccpage->module->slug."/manage-posts",
		'text' => $lang['Edit another comic post']
	),
	array(
		'link' => $ccurl . $navslug.'/'.$ccpage->module->slug."/add-post",
		'text' => $lang['Add another comic post']
	),
	array(
		'link' => $ccsite->root . $ccpage->module->slug . '/' . $thiscomic['slug'],
		'text' => str_replace('%s',htmlentities($thiscomic['title']),$lang['Preview %s'])
	)
);
quickLinks($links);

?>

<main id="content">

<?php 

//if post not found, return error
if(empty($thiscomic)){
	echo '<div class="msg error f-c">' . $lang['No comic was found with this information.'] . '</div>';
}
else{

	//submit page if posted
	if(isset($_POST) && $_POST['comic-title'] != ""){
		
		//set values for the query 
		$comic = $ccpage->module->id;
		if(isset($_POST['image-finalfile']) && $_POST['image-finalfile'] != ""){
			$imgname = $_POST['image-finalfile'];
		}else{
			$imgname = $thiscomic['imgname'];
		}
		if(isset($_POST['image-thumbfile']) && $_POST['image-thumbfile'] != ""){
			$comicthumb = $_POST['image-thumbfile'];
		}else{
			$comicthumb = $thiscomic['comicthumb'];
		}
		$comicstatic = $thiscomic['comicstatic'];
		$timestring = $_POST['comic-date'] . ' ' . sprintf('%02d',$_POST['hour']) . ':' . sprintf('%02d',$_POST['minute']) . ':' . sprintf('%02d',$_POST['second']);
		$publishtime = strtotime($timestring);
		$title = $_POST['comic-title'];
		$newstitle = $_POST['news-title'];
		$newscontent = trim($_POST['news-content']);
		$transcript = $_POST['comic-transcript'];
		$storyline = $_POST['comic-storyline'];
		$hovertext = $_POST['comic-hovertext'];
		$imginfo = getimagesize('../comics/' . $imgname);
		$width = $imginfo[0];
		$height = $imginfo[1];
		$mime = $imginfo['mime'];
		$contentwarning = $_POST['comic-content-warning'];
		$altnext = $_POST['comic-alternative-link'];
		$slugfinal = $thiscomic['slug'];
		$isanimated = $thiscomic['isanimated'];
		$newisanimated = 0;
		if(strcmp($_POST['isanimated'],'on') == 0) {
			$newisanimated = 1;
			if (isset($_POST['image-staticfile']) && $_POST['image-staticfile'] != "") {
							$comicstatic = $_POST['image-staticfile'];
			}
		}
		//did the animate property change
		if ($isanimated != $newisanimated) {
			$isanimated = $newisanimated;
			//if new val is Yes
			if($isanimated == 1) {
				$comicstatic = $_POST['image-staticfile'];
			}else {
				$comicstatic = $imgname;
			}
		}

		//Extra pages
		$extra1 = $_POST['image-extra1file'];
		$extraimginfo = getimagesize('../comicsextra/' . $extra1);
		$extra1mime = $extraimginfo['mime'];
		$extra2 = $_POST['image-extra2file'];
		$extraimginfo = getimagesize('../comicsextra/' . $extra2);
		$extra2mime = $extraimginfo['mime'];
		$extra3 = $_POST['image-extra3file'];
		$extraimginfo = getimagesize('../comicsextra/' . $extra3);
		$extra3mime = $extraimginfo['mime'];


		//execute query
		$query = "UPDATE cc_" . $tableprefix . "comics SET comic=:comic,comicstatic=:comicstatic,comicthumb=:comicthumb,imgname=:imgname,publishtime=:publishtime,title=:title,newstitle=:newstitle,newscontent=:newscontent,transcript=:transcript,storyline=:storyline,hovertext=:hovertext,width=:width,height=:height,mime=:mime,contentwarning=:contentwarning,altnext=:altnext,isanimated=:isanimated WHERE id=:id";
		$stmt = $cc->prepare($query);
		$stmt->execute(['comic' => $comic, 'comicstatic' => $comicstatic, 'comicthumb' => $comicthumb, 'imgname' => $imgname, 'publishtime' => $publishtime, 'title' => $title, 'newstitle' => $newstitle, 'newscontent' => $newscontent, 'transcript' => $transcript, 'storyline' => $storyline, 'hovertext' => $hovertext, 'width' => $width, 'height' => $height, 'mime' => $mime, 'contentwarning' => $contentwarning, 'altnext' => $altnext, 'isanimated' => $isanimated, 'id' => $thiscomic['id']]);
		
		//continue if post successfully edited
		if($stmt->rowCount() > 0){
			
			//reset tags
			$stmt = $cc->prepare("DELETE FROM cc_" . $tableprefix . "comics_tags WHERE comic=:comic AND comicid=:comicid");
			$stmt->execute(['comic' => $comic,'comicid' => $thiscomic['id']]);
			
			$tags = str_replace(", ",",",$_POST['comic-tags']);
			$tags = explode(",",$tags);
			$stmt = $cc->prepare("INSERT INTO cc_" . $tableprefix . "comics_tags(comic,comicid,tag,publishtime) VALUES(:moduleid,:postid,:tag,:publishtime)");
			foreach($tags as $tag){
				$tag = trim($tag);
				if($tag != ""){
					$stmt->execute(['moduleid' => $comic, 'postid' => $thiscomic['id'], 'tag' => $tag, 'publishtime' => $publishtime]);
				}
			}

			//add/update the extra pages if needed
			$eorder = 1;
			$extraid = 0;
			if ($extra1 != "") {
				if($extraPages[$eorder] != "") {
					//update page
					$extraid = $extraPageArr[0]['id'];
					$query = "UPDATE cc_" . $tableprefix . "comics_extra SET comic=:comic,comicid=:comicid,imgname=:imgname,mime=:mime,eorder=:eorder WHERE id=:id";
					$stmt = $cc->prepare($query);
					$stmt->execute(['comic' => $comic, 'comicid' => $thiscomic['id'], 'imgname' => $extra1, 'mime' => $extra1mime, 'eorder' => $eorder, 'id' => $extraid]);
				}else{
					//add page
					$stmt = $cc->prepare("INSERT INTO cc_" . $tableprefix . "comics_extra(comic,comicid,imgname,mime,eorder) VALUES(:moduleid,:postid,:extra,:extramime,:eorder)");
					$stmt->execute(['moduleid' => $comic, 'postid' => $thiscomic['id'], 'extra' => $extra1, 'extramime' => $extra1mime, 'eorder' => $eorder]);
				}
			}
			if ($extra2 != "") {
				$eorder = 2;
				if($extraPages[$eorder] != "") {
					//update page
					$extraid = $extraPageArr[1]['id'];
					$query = "UPDATE cc_" . $tableprefix . "comics_extra SET comic=:comic,comicid=:comicid,imgname=:imgname,mime=:mime,eorder=:eorder WHERE id=:id";
					$stmt = $cc->prepare($query);
					$stmt->execute(['comic' => $comic, 'comicid' => $thiscomic['id'], 'imgname' => $extra1, 'mime' => $extra1mime, 'eorder' => $eorder, 'id' => $extraid]);
				}else{
					//add page
					$stmt = $cc->prepare("INSERT INTO cc_" . $tableprefix . "comics_extra(comic,comicid,imgname,mime,eorder) VALUES(:moduleid,:postid,:extra,:extramime,:eorder)");
					$stmt->execute(['moduleid' => $comic, 'postid' => $thiscomic['id'], 'extra' => $extra2, 'extramime' => $extra2mime, 'eorder' => $eorder]);
				}
			}
			if ($extra3 != "") {
				$eorder = 3;
				if($extraPages[$eorder] != "") {
					//update page
					$extraid = $extraPageArr[2]['id'];
					$query = "UPDATE cc_" . $tableprefix . "comics_extra SET comic=:comic,comicid=:comicid,imgname=:imgname,mime=:mime,eorder=:eorder WHERE id=:id";
					$stmt = $cc->prepare($query);
					$stmt->execute(['comic' => $comic, 'comicid' => $thiscomic['id'], 'imgname' => $extra1, 'mime' => $extra1mime, 'eorder' => $eorder, 'id' => $extraid]);
				}else{
					//add page
					$stmt = $cc->prepare("INSERT INTO cc_" . $tableprefix . "comics_extra(comic,comicid,imgname,mime,eorder) VALUES(:moduleid,:postid,:extra,:extramime,:eorder)");
					$stmt->execute(['moduleid' => $comic, 'postid' => $thiscomic['id'], 'extra' => $extra3, 'extramime' => $extra3mime, 'eorder' => $eorder]);
				}
			}


			?>
			<div class="msg success f-c"><?=str_replace('%s',$title,$lang['%s has been successfully edited.'])?></div>
			<?php		
			echo '<div class="cc-btn-row">';
			buildButton(
				"light-bg",
				$ccurl . $navslug . '/' . $ccpage->module->slug . '/edit-post/' . $slugfinal,
				str_replace('%s',htmlentities($title),$lang['Edit %s again'])
			);
			buildButton(
				"light-bg",
				$ccsite->root . $ccpage->module->slug . '/' . $slugfinal,
				str_replace('%s',htmlentities($title),$lang['Preview %s'])
			);
			echo '</div>';
			
		}
			
		//output error message if failed
		else{
			?>
			<div class="msg error f-c"><?=$lang['There was an error editing your comic post.  Please try again.']?></div>
			<?php
			echo '<div class="cc-btn-row">';
			buildButton(
				"dark-bg",
				$navslug . '/' . $ccpage->module->slug . '/add-post',
				$lang['Add a new comic post']
			);
			buildButton(
				"dark-bg",
				$navslug . '/' . $ccpage->module->slug . '/manage-posts',
				$lang['Edit a different comic post']
			);
			buildButton(
				"dark-bg",
				$navslug . '/' . $ccpage->module->slug . '/',
				str_replace('%s',$ccpage->title,$lang['Return to managing %s'])
			);
			echo '</div>';
		}
		
	}else{
		
		//start the form ?>

		<form action="" method="post" enctype="multipart/form-data">
			
			<?php // comic uploader area ?>
			<div class="currentfileholder"><button class="full-width dark-bg toggle-current-file"><span class="current-file-text"><?=$lang['View current file']?></span> <i class="fa fa-angle-down"></i></button>
				<div class="currentfile"><img src="<?=$ccsite->root . 'comics/' . $thiscomic['imgname']?>" /></div>
				<span class="currentfinalfile"><?=$thiscomic['imgname']?></span>
			</div>
			<?php buildImageInput($lang['Change file...'],false); ?>
				
			<?php //build the comic info form ?>
			<h2 class="formheader"><?=$lang['Comic info']?></h2>
			<div class="formcontain">
				<?php
					//build array of form info
					$forminputs = array();
					$currentIsAnimated = 'off';
					$firstOptionAnimated = 'off';
					$secondOptionAnimated = 'on';
					$firstOptionAnimVal = $lang['No'];
					$secondOptionAnimVal = $lang['Yes'];
					if($thiscomic['isanimated'] == 1) {
						$currentIsAnimated = 'on';
						$firstOptionAnimated = 'on';
						$secondOptionAnimated = 'off';
						$firstOptionAnimVal = $lang['Yes'];
						$secondOptionAnimVal = $lang['No'];
					}
					array_push($forminputs,
						array(
							array(
								'type' => "text",
								'label' => $lang['Comic title'],
								'tooltip' => $lang['tooltip-comictitle'],
								'name' => "comic-title",
								'regex' => "normal-text",
								'current' => $thiscomic['title']
							)
						),array(
							array(
								'type' => "date",
								'label' => $lang['Publish date'],
								'tooltip' => $lang['tooltip-publishtime'],
								'name' => "comic-date",
								'regex' => "date",
								'current' => date("m/d/Y",$thiscomic['publishtime'])
							),array(
								'type' => "time",
								'label' => $lang['Publish time'],
								'tooltip' => $lang['tooltip-publishtime'],
								'name' => "comic-time",
								'regex' => "time",
								'current' => $thiscomic['publishtime']
							)
						),array(
							array(
								'type' => "text",
								'label' => $lang['Hovertext'],
								'tooltip' => $lang['tooltip-hovertext'],
								'name' => "comic-hovertext",
								'regex' => false,
								'current' => $thiscomic['hovertext']
							)
						),array(
							array(
								'type' => "text",
								'label' => $lang['Alternative link'],
								'tooltip' => $lang['tooltip-alternativelink'],
								'name' => "comic-alternative-link",
								'regex' => false,
								'current' => $thiscomic['altnext']
							)
						),array(
							array(
								'type' => "storylines",
								'label' => $lang['Storyline'],
								'tooltip' => $lang['tooltip-storyline'],
								'name' => "comic-storyline",
								'regex' => "storyline",
								'current' => $thiscomic['storyline']
							)
						),array(
							array(
								'type' => "select",
								'label' => $lang['Is animated'],
								'tooltip' => $lang['tooltip-isanimated'],
								'name' => 'isanimated',
								'options' => array(
									$firstOptionAnimated => $firstOptionAnimVal,
									$secondOptionAnimated => $secondOptionAnimVal,
								),
								'current' => $currentIsAnimated
							)
						)
					);
					if(getModuleOption('contentwarnings') == "on") array_push($forminputs,
						array(
							array(
								'type' => "text",
								'label' => $lang['Content warning'],
								'tooltip' => $lang['tooltip-contentwarning'],
								'name' => "comic-content-warning",
								'regex' => false,
								'current' => $thiscomic['contentwarning']
							)
						)
					);
					
					//build the form
					buildForm($forminputs) 
				?>
			</div>
			<span class="currentthumbfile"><?=$thiscomic['comicthumb']?></span>
			<?php buildThumbnailFileInput(false); ?>

			<span id="currentisanimated" style="visibility:hidden;"><?=$thiscomic['isanimated']?></span>
			<span class="currentstaticfile"><?=$thiscomic['comicstatic']?></span>
			<?php buildCustomFileInput("Static Image (for animated pages)",false,"","static"); ?>

			<?php 
				//get current extra pages 
				$stmt = $cc->prepare("SELECT * FROM cc_" . $tableprefix . "comics_extra WHERE comic=:comic AND comicid=:comicid ORDER BY eorder");
				$stmt->execute(['comic' => $thiscomic['comic'],'comicid' => $thiscomic['id']]);
				$extraPageArr = $stmt->fetchAll();
				$extraPages = array(1 => "", 2 => "", 3 => "");
				foreach($extraPageArr as $extraPage){
					echo '<span class="currentextra' . $extraPage['eorder'] . 'file">EXTRA' . $extraPage['eorder'] . ' = ' . $extraPage['imgname'] . '</span><br>';
					$extraPages[$extraPage['eorder']] = $extraPage['imgname'];
				}
			?>

			<?php buildCustomFileInput("Extra Comic Page/Overlay 1",false,"","extra1"); ?>
			<?php buildCustomFileInput("Extra Comic Page/Overlay 2",false,"","extra2"); ?>
			<?php buildCustomFileInput("Extra Comic Page/Overlay 3",false,"","extra3"); ?>


			<?php //build the news post form ?>
			<h2 class="formheader">News post</h2>
			<div class="formcontain">
			<?php
				//get tags
				$stmt = $cc->prepare("SELECT * FROM cc_" . $tableprefix . "comics_tags WHERE comic=:comic AND comicid=:comicid");
				$stmt->execute(['comic' => $thiscomic['comic'],'comicid' => $thiscomic['id']]);
				$tagarr = $stmt->fetchAll();
				$tags = "";
				foreach($tagarr as $tag){
					$tags .= $tag['tag'] . ', ';
				}
				
			
				//build array of form info
				$forminputs = array();
				array_push($forminputs,
					array(
						array(
							'type' => "text",
							'label' => $lang['News title'],
							'tooltip' => $lang['tooltip-newstitle'],
							'name' => "news-title",
							'regex' => false,
							'current' => $thiscomic['newstitle']
						)
					)
				);
				if(getModuleOption('displaytags') == "on") 
					array_push($forminputs,
						array(
							array(
								'type' => "text",
								'label' => $lang['Tags'],
								'tooltip' => $lang['tooltip-tags'],
								'name' => "comic-tags",
								'regex' => false,
								'current' => $tags
							)
						)
					);
				
				//build the form
				buildForm($forminputs);
				buildTextEditor($lang['News content'],"news-content",$lang['tooltip-newscontent'],$thiscomic['newscontent']);
				if(getModuleOption('displaytranscript') == "on") buildTextEditor($lang['Comic transcript'],"comic-transcript",$lang['tooltip-transcript'],$thiscomic['transcript']);
			?>	
			</div>
			<button class="full-width light-bg" style="margin-top:20px;" type="button" id="submitform"><?=$lang['Submit changes']?></button>
		</form>
		<script>
		$('.toggle-current-file').on('click',function(e){
			e.preventDefault();
			$(this).parent().find('.currentfile').slideToggle();
			$(this).find('.current-file-text').text(function(i, text){
				  return text === 'View current file' ? 'Hide current file' : 'View current file';
			});
			$(this).find('i').toggleClass('fa-angle-down fa-angle-up');
		});
		$('#isanimated').on('change',function() {
			if($('#currentisanimated').text() == '0') {
				if($(this).val() == 'on') {
					$('#staticfile').attr('data-validate', 'custom-file-upload');
				} else {
					$('#staticfile').removeAttr('data-validate');
				}
			}
		});

		</script>
		<?php 
		//include relevant javascript
		$imgfolder = "comics/";
		include('includes/form-submit-js.php');
		include('includes/img-upload-js.php');
		include('includes/content-editor-js.php');
		include('includes/img-custom-upload-js.php');

	}
}
?>

</main>