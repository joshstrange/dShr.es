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
				background: url('/img/btn-get-dropbox-link.png') bottom;
				vertical-align: middle;
				/*text-indent: -99999px;*/
			}
			.getLink:hover {
				background-position: 0 0;
				cursor:pointer;
			}
			.pubLink {
				display: inline-block;
				width: 181px;
				height: 27px;
				background: url('/img/btn-download.png') bottom;
				vertical-align: middle;
				/*text-indent: -99999px;*/
			}
			.pubLink:hover {
				background-position: 0 0;
				cursor:pointer;
			}

			.copyRef {
				display: inline-block;
				width: 181px;
				height: 27px;
				background: url('/img/btn-copy-to-your-dropbox.png') bottom;
				vertical-align: middle;
				/*text-indent: -99999px;*/
			}
			.copyRef:hover {
				background-position: 0 0;
				cursor:pointer;
			}
		</style>
		<script type="text/javascript">
            $(function() {

            });
            function copyToDB(hash)
            {
				$('#copyRef').attr('href', '#');
				$('#copyRef').css('background-image', 'url(http://drop.sh/img/btn-copying-1.png)');
				var interval = setInterval("copying()",250);
				$.getJSON('/addToDB/'+hash, function(data) {
					if(!data.error)
					{
						clearInterval(interval);
						$('#copyRef').css('background-image', 'url(http://drop.sh/img/btn-copied.png)');
					}
					else
						alert(data.error)
				});
            }
            function copying()
            {
            	var image = $('#copyRef').css('background-image');
            	var number = image.replace('url(http://drop.sh/img/btn-copying-','');
            	number = number.replace('.png)','');
            	if(number==4) number=0;
            	number++;
            	$('#copyRef').css('background-image', 'url(http://drop.sh/img/btn-copying-'+number+'.png)');

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
			<table>
				<tr>
					<td>
						<img src="/img/48x48/<?=$share->icon?>.gif">
					</td>
					<td>
						<h5>File: <?=$share->filename?></h5>
						<h5>Size: <?=$share->size?></h5>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>
						<a class="pubLink" href="<?=$share->publiclink?>"></a>
					</td>
					<td>
						<a class="copyRef" id="copyRef" href="javascript:copyToDB('<?=$share->urlhash?>')"></a>
					</td>
				</tr>
			</table>
			<?php
				
				
			?>
		</div>
	</body>
</html>