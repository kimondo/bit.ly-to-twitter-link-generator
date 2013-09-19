<?php


// edit these settings 
$source = 'kimondo';

// just your bit.ly username

$bitlyusername = 'username';


// generate your bit.ly app key here: https://bitly.com/a/your_api_key 

$bitlyAPIkey = 'api key';

//


/* make a URL small */
function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
{
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
	
	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);
	
	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

// ------------------------------------------ post + filter data from the form ---------------------------------

// get the tweet text

$tweet = $_POST['tweet'];

$tweet = urlencode($tweet);

// include the source (see settings above)

$output = 'https://twitter.com/intent/tweet?text='.$tweet.'&source='.$source; 

// generate the bit.ly link

$short = make_bitly_url($output,$bitlyusername,$bitlyAPIkey,'json');


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>bit.ly twitter link generator</title>
<link href="style.css" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />


<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

</head>

<body>

<div id="container">
  <div id="webtivistform">
    <h1>Bit(.ly) to tweet link generator</h1>
    <p>Here\'s your tweet link:</p>';
    
echo '<a href="'.$output.'">'.$output.'</a>';
    
echo '<p>Here\'s your bit.ly link:</p>';

echo '<a href="'.$short.'">'.$short.'</a>';

echo '<p>Here\'s your bit.ly tracking link:</p>';

echo '<a href="'.$short.'+">'.$short.'+</a>';
    
echo '<p>  
<input type="submit" value="Generate another link" 
       onclick="return btntest_onclick()" />
<script language="javascript" type="text/javascript">

function btntest_onclick() 
{
    window.location.href = "index.html";
}

</script>

</p>



    </div></div></body>
</html>';

 
?>  