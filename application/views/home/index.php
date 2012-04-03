<!doctype html>
<html>
	<head>
		<meta charset="utf-8">

		<title>Drop.Sh/are - #!/usr/bin/sharing</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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
				padding: 0 30px;
			}

			#main li {
				margin: 5px 0;
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
			.getLink .ajax {
				background-image: url('/img/ajax.gif');
				width: 16px;
				height: 11px;
				z-index: 10;
				float: right;
				margin-top: 5px;
				margin-right: 10px;
			}
			.getLink:hover {
				background-position: 0 0;
				cursor:pointer;
			}
			#main input{
				width:181px;
			}
		</style>
		<script type="text/javascript">
            $(function() {

            });
            function getLink(path,icon,size,pos)
            {
				$('#dbshare_'+pos).html('<span class="ajax"></span>')
				$.getJSON('/getDSLink?path='+path+'&icon='+icon+'&size='+size, function(data) {
					if(!data.error)
					{
						var link = data.url;
						//$('#dbshare_'+pos).html('<input value="'+link+'">');
					}
					else
						alert(data.error)
				});
            }
        </script>
	</head>
	<body>
		<div id="main">
			<h1>Drop.Sh/are<img style="float:right;" src="/img/dropshare-logo-150x200.png"></h1>

			<h2>#!/usr/bin/sharing</h2>

			<p>
				<a href="http://Drop.Sh/are">Drop.Sh/are</a> make it super easy to share <a href="http://dropbox.com">Dropbox</a> files with anyone! 
			</p>
			<?php
				if($loggedIn)
				{
					?>
					<p>
						To share files using <a href="http://Drop.Sh/are">Drop.Sh/are</a> all you have to do is move the file(s) you want to share into your /Apps/Drop.sh/ Folder and they will show up below! 
					</p>

					<h3>Your Files</h3>
					<?php
					$metaData = $dropbox->metaData('/');
					$files = $metaData['body']->contents;
					?>
					<table>
					<?php
					$count =0;
					foreach($files as $file)
					{
						$count++;
						$path = $file->path;
						$filePathArray = explode('/',$path);
						$filename = $filePathArray[count($filePathArray)-1];
						//print_r($file);
						echo '<tr><td><img src="/img/16x16/'.$file->icon.'.gif"></td><td>'.$filename.'</td><td id="dbshare_'.$count.'"><a href="javascript:getLink(\''.$path.'\',\''.$file->icon.'\',\''.$file->size.'\',\''.$count.'\')" class="getLink"></a></td></tr>';
					}

					?>
					</table>
					<?php
				}
				else
				{
					?>
					<h3><a href="/linkdropbox">Link Your Dropbox!</a></h3>
					<?php
				}
				
			?>
		</div>
	</body>
</html>