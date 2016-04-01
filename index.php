<head>
  <link href="http://vjs.zencdn.net/5.4.4/video-js.css" rel="stylesheet">
<link rel="stylesheet" href="panel.css">
  <!-- If you'd like to support IE8 -->
  <script src="http://vjs.zencdn.net/ie8/1.1.1/videojs-ie8.min.js"></script>
</head>


<body>
<?php
if (isset($_POST['id']))
	{
	$id = $_POST['id'];
	}
elseif (isset($_GET['id']))
	{
	$id = $_GET['id'];
	}

if (isset($id))
{
	parse_str($id,$test);
	$id = $test['https://www_youtube_com/watch?v'];
	parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=".$id),$info);
	if (!file_exists($id.".mp4"))
		{

		$streams = $info['url_encoded_fmt_stream_map'];
		$streams = explode(',',$streams);
		foreach($streams as $stream){
		    parse_str($stream,$data); //decode the stream
		    if (isset($main[$data['quality']]['quality']))
			{
			$main[$data['quality']."_bis"] = $data;
			} else
			{
			    $main[$data['quality']] = $data;
			}
	
		$video = fopen(array_values($main)[0]["url"],'r');
		$file = fopen($id.".mp4",'w');
		stream_copy_to_stream($video,$file);
		fclose($video);
		fclose($file);
		}
	}
	echo @'
<div class="panel"><center>
    <h1>'.$info['title'].'</h1>
</center>
  <video id="my-video" class="video-js panel" controls preload="auto" width="640" height="264"
  poster="'.$info['iurlhq'].'" data-setup="{}">
    <source src="'.$id.'.mp4" type="video/mp4">
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that
      <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>
</div>

  <script src="http://vjs.zencdn.net/5.4.4/video.js"></script>';

}
	


?>
<br><center>
<form action="poke.php" class="panel" method="post">
  Youtube Address: <input type="text" name="id"><br>
  <input type="submit" value="Submit">
</form>
</center>
</body>
