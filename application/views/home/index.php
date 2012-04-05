<!doctype html>
<html>
	<head>
		<meta charset="utf-8">

		<title>dShr.es - #!/usr/bin/sharing</title>
		<link rel="icon" type="image/png" href="/favicon.png">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
		<script type="text/javascript" src="/js/plupload.full.js"></script>
		<style>
			@import url(http://fonts.googleapis.com/css?family=Ubuntu);

			body {
				background: #eee;
				color: #6d6d6d;
				font: normal normal normal 14px/1.253 Ubuntu, sans-serif;
				margin: 0 0 25px 0;
				min-width: 800px;
				padding: 0;
			}
			body a, body a:visited, body a:hover, body a:active {
				color: #6d6d6d;
			}
			#main {
				background-clip: padding-box;
				background-color: #fff;
				border:1px solid #ccc;
				border-radius: 5px;
				box-shadow: 0 0 10px #cdcdcd;
				margin: 25px auto 0;
				padding: 30px;
				width: 700px;
				position: relative;
			}

			#main h1 {
				font-family: 'Ubuntu';
				font-size: 38px;
				letter-spacing: 2px;
				margin: 0 0 10px 0;
				padding: 0;
			}

			#main h2 {
				color: #999;
				font-size: 18px;
				letter-spacing: 3px;
				margin: 0 0 25px 0;
				padding: 0 0 0 0;
			}

			#main h3 {
				color: #999;
				margin-top: 24px;
				padding: 0 0 0 0;
			}

			#main h3 {
				font-size: 18px;
			}

			#main p {
				line-height: 25px;
				margin: 10px 0;
			}

			#main pre {
				background-color: #333;
				border-left: 1px solid #d8d8d8;
				border-top: 1px solid #d8d8d8;
				border-radius: 5px;
				color: #eee;
				padding: 10px;
			}

			#main div.warning {
				background-color: #feefb3;
				border: 1px solid;
				border-radius: 5px;
				color: #9f6000;
				padding: 10px;
			}

			#main ul {
				margin: 10px 0;
				padding-left: 30px;
				list-style:none;
			}
			#main ul ul {
				/*margin-left: 30px;*/
				margin-right: 0px;
				padding-right: 0px;
			}
			#main li:hover > .getLinkSpan {
				display:block;
			}
			#main li:hover {
				background-color: #EEE;
				border-radius: 5px;
			}
			#main li {
				margin: 5px 0;
				height:27px;
				padding-left: 5px;
			}
			img {vertical-align: middle;}

			.getLink {
				display: inline-block;
				width: 181px;
				height: 27px;
				background: url('/img/btn-get-dropshare-link.png') bottom;
				vertical-align: middle;
				/*text-indent: -99999px;*/
			}
			.getLink:hover {
				background-position: 0 0;
				cursor:pointer;
			}
			.getLinkSpan {
				float:right;
				display:none;
			}
			.linkDropbox {
				display: inline-block;
				width: 181px;
				height: 27px;
				background: url('/img/btn-link-with-dropbox.png') bottom;
				vertical-align: middle;
				/*text-indent: -99999px;*/
			}
			.linkDropbox:hover {
				background-position: 0 0;
				cursor:pointer;
			}
			.filename {
				margin-top: 5px;
				position: absolute;
				margin-left: 20px;
			}
			.fileIcon {
				margin-top: 5px;
				position: absolute;
			}
			#main input{
				width:175px;
				margin-top: 3px;
			}
			#dropfile {
				width:700px;
				height:200px;
				border-radius:10px;
				background:#EEE;
				border: dashed;
			}
			#dropfile .filename {
				float: left;
				font-size: 16px;
				clear: left;
			}
			#dropfile .title {
				padding: 10px;
				font-size: 21px;	
			}
			#filelist {
				height: 250px;
				top:35px;
				padding-left:10px;
				font-size:12px;
				overflow-y: scroll;
			}
			.meter { 
			height: 5px;  /* Can be anything */
			float:right;
			margin-right:10px;
			position: relative;
			background: #555;
			width:50%;
			-moz-border-radius: 25px;
			-webkit-border-radius: 25px;
			border-radius: 25px;
			padding: 10px;
			-webkit-box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
			-moz-box-shadow   : inset 0 -1px 1px rgba(255,255,255,0.3);
			box-shadow        : inset 0 -1px 1px rgba(255,255,255,0.3);
		}
		.meter > span {
			display: block;
			height: 6px;
			width:50%;
			   -webkit-border-top-right-radius: 8px;
			-webkit-border-bottom-right-radius: 8px;
			       -moz-border-radius-topright: 8px;
			    -moz-border-radius-bottomright: 8px;
			           border-top-right-radius: 8px;
			        border-bottom-right-radius: 8px;
			    -webkit-border-top-left-radius: 20px;
			 -webkit-border-bottom-left-radius: 20px;
			        -moz-border-radius-topleft: 20px;
			     -moz-border-radius-bottomleft: 20px;
			            border-top-left-radius: 20px;
			         border-bottom-left-radius: 20px;
			background-color: rgb(43,194,83);
			background-image: -webkit-gradient(
			  linear,
			  left bottom,
			  left top,
			  color-stop(0, rgb(43,194,83)),
			  color-stop(1, rgb(84,240,84))
			 );
			background-image: -moz-linear-gradient(
			  center bottom,
			  rgb(43,194,83) 37%,
			  rgb(84,240,84) 69%
			 );
			-webkit-box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			-moz-box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			box-shadow: 
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			position: relative;
			overflow: hidden;
		}
		.meter > span:after, .animate > span > span {
			content: "";
			position: absolute;
			top: 0; left: 0; bottom: 0; right: 0;
			background-image: 
			   -webkit-gradient(linear, 0 0, 100% 100%, 
			      color-stop(.25, rgba(255, 255, 255, .2)), 
			      color-stop(.25, transparent), color-stop(.5, transparent), 
			      color-stop(.5, rgba(255, 255, 255, .2)), 
			      color-stop(.75, rgba(255, 255, 255, .2)), 
			      color-stop(.75, transparent), to(transparent)
			   );
			background-image: 
				-moz-linear-gradient(
				  -45deg, 
			      rgba(255, 255, 255, .2) 25%, 
			      transparent 25%, 
			      transparent 50%, 
			      rgba(255, 255, 255, .2) 50%, 
			      rgba(255, 255, 255, .2) 75%, 
			      transparent 75%, 
			      transparent
			   );
			z-index: 1;
			-webkit-background-size: 50px 50px;
			-moz-background-size: 50px 50px;
			-webkit-animation: move 2s linear infinite;
			   -webkit-border-top-right-radius: 8px;
			-webkit-border-bottom-right-radius: 8px;
			       -moz-border-radius-topright: 8px;
			    -moz-border-radius-bottomright: 8px;
			           border-top-right-radius: 8px;
			        border-bottom-right-radius: 8px;
			    -webkit-border-top-left-radius: 20px;
			 -webkit-border-bottom-left-radius: 20px;
			        -moz-border-radius-topleft: 20px;
			     -moz-border-radius-bottomleft: 20px;
			            border-top-left-radius: 20px;
			         border-bottom-left-radius: 20px;
			overflow: hidden;
		}
		
		.animate > span:after {
			display: none;
		}
		
		@-webkit-keyframes move {
		    0% {
		       background-position: 0 0;
		    }
		    100% {
		       background-position: 50px 50px;
		    }
		}
		</style>
		<script type="text/javascript">
            $(function() {
				var uploader = new plupload.Uploader({
					runtimes : 'gears,html5,flash,silverlight,browserplus',
					browse_button : 'pickfiles',
					container : 'container',
					max_file_size : '150mb',
					url : 'fileupload',
					flash_swf_url : '/lib/plupload.flash.swf',
					silverlight_xap_url : '/lib/plupload.silverlight.xap',
					drop_element: 'dropfile'
				});


				$('#uploadfiles').click(function(e) {
					uploader.start();
					e.preventDefault();
				});

				uploader.init();

				uploader.bind('FilesAdded', function(up, files) {
					$.each(files, function(i, file) {
						$('#filelist').append(
							'<div id="' + file.id + '"><div class="filename">' +
							file.name + ' (' + plupload.formatSize(file.size) + ')</div> <b><div id="p_' + file.id + '" class="meter animate"><span style="width: 0%"></span></div></b>' +
						'</div>');
					});
					$(".meter > span").each(function() {
						$(this)
							.data("origWidth", $(this).width())
							.width(0)
							.animate({
								width: $(this).data("origWidth")
							}, 1200);
					});
					uploader.start();
					up.refresh(); // Reposition Flash/Silverlight
				});

				uploader.bind('UploadProgress', function(up, file) {
					//$('#' + file.id + " b").html(file.percent + "%");
					$('#p_' + file.id+' span').css('width',file.percent + "%");
				});

				uploader.bind('Error', function(up, err) {
					$('#filelist').append("<div>Error: " + err.code +
						", Message: " + err.message +
						(err.file ? ", File: " + err.file.name : "") +
						"</div>"
					);

					up.refresh(); // Reposition Flash/Silverlight
				});
				//setInterval('updateFileList()',10000); //Update every 10 seconds - Commented out during testing of new code
				uploader.bind('FileUploaded', function(up, file) {
					//$('#' + file.id + " b").html("100%");
					$('#' + file.id + "").fadeOut("slow");
					updateFileList();
				});
            });
            function getLink(path,icon,size,pos)
            {
				$('#dbshare_'+pos).css('display','block');
				$('#dbshare_'+pos+' a').css('background-image', 'url(/img/btn-retrieving-link-1.png)');
				var interval = setInterval("waiting("+pos+")",250);
				$.getJSON('/getDSLink?path='+path+'&icon='+icon+'&size='+size, function(data) {
					if(!data.error)
					{
						clearInterval(interval);
						var link = data.url;
						$('#dbshare_'+pos).html('<input value="'+link+'">');
						$('#dbshare_'+pos+' input').focus();
						$('#dbshare_'+pos+' input').select();
					}
					else
						alert(data.error)
				});
            }
            function waiting(pos)
            {
            	var image = $('#dbshare_'+pos+' a').css('background-image');
            	var number = image.replace('url(/img/btn-retrieving-link-','');
            	number = number.replace('.png)','');
            	if(number==4) number=0;
            	number++;
            	$('#dbshare_'+pos+' a').css('background-image', 'url(/img/btn-retrieving-link-'+number+'.png)');

            }
            function updateFileList()
            {
				$.get('/getFileList', function(data) {
					//console.log('page: '+$('#dbFileList').html().length +' remote:'+data.length);
					//console.log($('#dbFileList').html());
					//console.log(data);
					if($('#dbFileList').html().length != data.length)
					{
						//$('#dbFileList').html(data);
						$("#dbFileList").fadeOut("fast", function(){
							$("#dbFileList").html(data);
							$("#dbFileList").fadeIn("slow");
						});
					}
				});   	
            }
        </script>
	</head>
	<body>
		<a href="http://github.com/joshstrange/dShr.es"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/71eeaab9d563c2b3c590319b398dd35683265e85/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677261795f3664366436642e706e67" alt="Fork me on GitHub"></a>
		<div id="main">
			<a href="/are"><h1>dShr.es<img style="float:right;" src="/img/dropshare-logo-150x200.png"></h1></a>

			<h2>#!/usr/bin/sharing</h2>

			<p>
				<a href="http://dShr.es">dShr.es</a> makes it super easy to share <a href="http://dropbox.com">Dropbox</a> files with anyone! 
			</p>
			<?php
				if($loggedIn)
				{
					?>
					<p>
						To share files using <a href="http://dShr.es">dShr.es</a> all you have to do is move the file(s) you want to share into your /Apps/dShr.es/ Folder and they will show up below! 
					</p>

					<h3>Your Files</h3>
					<?php
					/*$metaData = $dropbox->metaData('/');
					$files = $metaData['body']->contents;
					$count =0;*/
					
					/*echo '<table id="dbFileList"><tbody>';
					foreach($files as $file)
					{
						$count++;
						$path = $file->path;
						$filePathArray = explode('/',$path);
						$filename = $filePathArray[count($filePathArray)-1];
						//print_r($file);
						if($file->is_dir)
						echo '<tr><td><img src="/img/16x16/'.$file->icon.'.gif"></td><td>'.$filename.'</td><td id="dbshare_'.$count.'"><a href="javascript:getLink(\''.$path.'\',\''.$file->icon.'\',\''.$file->size.'\',\''.$count.'\')" class="getLink"></a></td></tr>';
					}
					echo '</tbody></table>';*/

					function printData($path,$level=0,$dropbox)
					{
						$level++;
						$count=0;
						$metaData = $dropbox->metaData($path);
						$files = $metaData['body']->contents;
						echo "<ul>";
						foreach($files as $file)
						{
							$count++;
							$path = $file->path;
							$filePathArray = explode('/',$path);
							$filename = $filePathArray[count($filePathArray)-1];
							//print_r($file);
							if($file->is_dir)
							{
								echo '<li>
										<span class="fileIcon">
											<img src="/img/16x16/'.$file->icon.'.gif">
										</span>
										<span class="filename">'
										.$filename.
										'</span>
									  ';
								printData($file->path,$level,$dropbox);
								echo '</li>';
							}
							else
								echo '<li>
										<span class="fileIcon">
											<img src="/img/16x16/'.$file->icon.'.gif">
										</span>
										<span class="filename">'
										.$filename.
										'</span>
										<span class="getLinkSpan" id="dbshare_'.$level.'-'.$count.'">
											<a href="javascript:getLink(\''.$path.'\',\''.$file->icon.'\',\''.$file->size.'\',\''.$level.'-'.$count.'\')" class="getLink"></a>
										</span>
									  </li>';
						}
						echo "</ul>";

					}
					
					printData('/',0,$dropbox);
					


					?>
					
					<h3>Upload a file</h3>
					<div id="container">
						
						<br />
						<div id="dropfile">
							<span class="title">
								Drop Files Here or <a id="pickfiles" href="#">Browse...</a>
							<div id="filelist">
							</div>
						</div>
						<!--a id="uploadfiles" href="#">[Upload files]</a-->
					</div>
					<?php
				}
				else
				{
					?>
					<h3><a class="linkDropbox" href="/linkdropbox"></a></h3>
					<?php
				}
				
			?>

		</div>
	</body>
</html>