<?php
  echo '<div id="container">' . "\n";
  for ($i = $last; $i >= $last-30 && $i > 0; $i-- ){ 
    $message = $storage->getMessage($i);
    $mfile = $storage->getUniqueId($i);
    //echo "$mfile\n";
    // output first text/html part
    $foundPart = null;
    $jpgPart = null;
    foreach (new RecursiveIteratorIterator($message) as $part) {
        try {
            if (strtok($part->contentType, ';') == 'text/html') {
                $foundPart = $part;
		$type = "$mfile is text/html";
            } 
	    if (strtok($part->contentType, ';') == 'image/jpeg') {
		$jpgPart = $part;
		$type = $type . "has image/jpeg";
	    }
            //$type = "$mfile is $part->contentType";
        } catch (Zend_Mail_Exception $e) {
            // ignore
        }
    }
    if (!$foundPart) {
        $mbody = $message->getContent();
        $type = "No text/html part found or not multipart $mfile is $message->contentType";
        $mext = "html";
    } else {
        //echo "text/html part: \n" . $foundPart;
        $content = $foundPart->getContent();
        try {
	    switch ($foundPart->contentTransferEncoding) {
            case 'base64':
              $mbody = base64_decode($content);
              $type = $type . "base64: ";
              break;
            case 'quoted-printable':
              $mbody = quoted_printable_decode($content);
              $type = $type . "quoted-printable: ";
	      error_log("Decoding quoted-printable for $mfile");
              break;
	    default:
	      $mbody = $foundPart->getContent();
	    }
        } catch (Zend_Mail_Exception $e) {
    	    $mbody = $foundPart->getContent();
	}
        //$mbody = $foundPart;
        $mext = "html";
    }
    if ($jpgPart) {
      //echo "JPG!\n";
      $imgcontent = $jpgPart->getContent();
      try {
            switch ($jpgPart->contentTransferEncoding) {
            case 'base64':
              $mbody = base64_decode($imgcontent);
              $type = $type . "base64: ";
              break;
            default:
              $mbody = $jpgPart->getContent();
            }
        } catch (Zend_Mail_Exception $e) {
            //$mbody = $foundPart->getContent();
        }
       $mext = "jpg";
    }
    //$mbody = $message->getContent();
    file_put_contents("./users/$newdir/$mfile.$mext", "$mbody");
    $imgfile[$i] = "./users/$newdir/$mfile.jpg";
    $multiimgfile[$i] = "./users/$newdir/$mfile-0.jpg";
    if (!`grep "\<html\>" ./users/$newdir/$mfile.html`) {
        error_log("No HTML Tag in /users/$newdir/$mfile.html");
    }
    if (!file_exists($imgfile[$i])  && !file_exists($multiimgfile[$i])) {
	$convertout = `/usr/local/bin/wkhtmltoimage --quality 100 ./users/$newdir/$mfile.html ./users/$newdir/$mfile.png >> /var/log/stackeye/wkhtmltoimage.dbg; convert -quality 100 ./users/$newdir/$mfile.png -trim -resize 240 ./users/$newdir/$mfile.jpg`;
    }
    if ($jpgPart) {
	$convertout = `convert -quality 100 ./users/$newdir/$mfile.jpg -trim -resize 240 ./users/$newdir/$mfile.jpg`;
    }
    if (file_exists($multiimgfile[$i])) {
    echo '<div id="search' . $i . '" class="box"><img src="' . $multiimgfile[$i] . '">' . "\n";
    } else {
    echo '<div id="search' . $i . '" class="box"><img src="' . $imgfile[$i] . '">' . "\n";
    }
    echo '<h6 class="headers">From: ' . $storage->getMessage($i)->from . '</h6><h6 class="headers">Subject: ' . $storage->getMessage($i)->subject . '</h6><h6 class="headers">Date:' . $storage->getMessage($i)->date . '</h6></div>' . "\n";
    //echo '<div id="search' . $j . '" style="{border:1px solid blue}">' . 'UniqueID: ' . $storage->getUniqueId($searched[$j]) . "\nBody: " . $storage->getMessage($searched[$j])->getContent() . "</div>\n";
  }
  echo "</div>\n";
?>
