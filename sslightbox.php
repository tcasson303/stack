<!DOCTYPE html>
<html>
    <head>
        <title>Simple Lightbox</title>
<style>
body {
    margin:0;
    padding:0;
    background:#efefef;
    text-align:center; /* used to center div in IE */
}

#wrapper {
    width:600px; 
    margin:0 auto; 
    border-radius:0 0 5px 5px;
    -moz-border-radius:0 0 5px 5px;
    -webkit-border-radius: 0 0 5px 5px;
    background:#fff; 
    border:1px solid #ccc; 
    padding:25px; 
    border-top:none; 
    box-shadow:0 0 5px #ccc;
    -moz-box-shadow:0 0 5px #ccc;
    -webkit-box-shadow:0 0 5px #ccc;
    text-align:left;
}

#lightbox {
    position:fixed; /* keeps the lightbox window in the current viewport */
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:url(./overlay.png) repeat;
    text-align:center;
}

#lightbox p {
    text-align:right;
    color:#fff;
    margin-right:20px;
    font-size:12px;
}

#lightbox img {
    box-shadow:0 0 25px #111;
    -webkit-box-shadow:0 0 25px #111;
    -moz-box-shadow:0 0 25px #111;
    max-width:940px;
}
</style>
    </head>
<body>
<div id="wrapper">
    <h1>Super Simple Lightbox</h1>
    <p>Our super simple lightbox demo. Here are the image links:
        <ul>
            <li>
                <a href="https://farm7.static.flickr.com/6130/5935338876_47b61c93a5.jpg" class="lightbox_trigger">
                Picture 1
                </a>
            </li>
            <li>
                <a href="https://farm7.static.flickr.com/6020/5924329054_4bdc419c3a_o.jpg" class="lightbox_trigger">
                Picture 2
                </a>
            </li>
            <li>
                <a href="https://farm7.static.flickr.com/6020/5931933181_ddb737e528.jpg" class="lightbox_trigger">
                Picture 3
                </a>
            </li>
        </ul>
     </p>
</div> <!-- #/wrapper -->
        <script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
<script>
jQuery(document).ready(function($) {
	
	$('.lightbox_trigger').click(function(e) {
		
		//prevent default action (hyperlink)
		e.preventDefault();
		
		//Get clicked link href
		var image_href = $(this).attr("href");
		
		/* 	
		If the lightbox window HTML already exists in document, 
		change the img src to to match the href of whatever link was clicked
		
		If the lightbox window HTML doesn't exists, create it and insert it.
		(This will only happen the first time around)
		*/
		
		if ($('#lightbox').length > 0) { // #lightbox exists
			
			//place href as img src value
			$('#content').html('<img src="' + image_href + '" />');
		   	
			//show lightbox window - you could use .show('fast') for a transition
			$('#lightbox').show();
		}
		
		else { //#lightbox does not exist - create and insert (runs 1st time only)
			
			//create HTML markup for lightbox window
			var lightbox = 
			'<div id="lightbox">' +
				'<p>Click to close</p>' +
				'<div id="content">' + //insert clicked link's href into img src
					'<img src="' + image_href +'" />' +
				'</div>' +	
			'</div>';
				
			//insert lightbox HTML into page
			$('body').append(lightbox);
		}
		
	});
	
	//Click anywhere on the page to get rid of lightbox window
	$('#lightbox').live('click', function() { //must use live, as the lightbox element is inserted into the DOM
		$('#lightbox').hide();
	});

});
</script>
</body>
</html>
