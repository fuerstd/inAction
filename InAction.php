
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Im Einsatz?</title>
<meta name="author" content="Dominik F&uuml;rst, dominik.fuerst@zirking.at" />
<meta http-equiv="Content-type" content="text/html;charset=windows-1252" />

</head>
<body style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; line-height: 19.5px; background-color:#ffffff; color:#333333;">
<p>
<?php
	
	$XMLUrl = "http://intranet.ooelfv.at/webext2/rss/webext2_laufend.xml";
	//$XMLUrl = "http://intranet.ooelfv.at/webext2/rss/webext2_2Tage.xml";
	
	$validData = TRUE; 	
	//if(file_exists($XMLUrl))
	if(fopen($XMLUrl, 'r'))
	{
		$xml = simplexml_load_file($XMLUrl);
		
	}
	else
	{	
		echo "Keine Einsatzdaten verf&uuml;gbar!";
		$validData = FALSE;
	}
	
	if($validData)
	{
		$TargetFFId = '407219';
		//$TargetFFId = '403102';
		if(isset($_POST['FFNr']))
			$TargetFFId = $_POST['FFNr'];

		$FFName = NULL;
		
		foreach ($xml->resources->resource as $resource)
		{    
			if(strcmp($resource->attributes()->id,$TargetFFId) == 0)
			{
				$FFName = htmlentities($resource->attributes()->name, ENT_IGNORE,"UTF-8");
				$actionID = htmlentities($resource->usedat->attributes()->id,ENT_IGNORE,"UTF-8");
				$BrigadeStartTime = DateTime::createFromFormat('D, d M Y H:i:s O', $resource->usedat->startzeit);
				
			}
		}
	}
if($validData)
{
	if(is_null($FFName))
		if($TargetFFId == '407219')
			echo "Die FF Zirking ist zur Zeit nicht im Einsatz:<br/>Stets bereit Ihre Feuerwehr!";
		else
			echo "Die Feuerwehr mit der Nummer <b>".$TargetFFId."</b> ist zur Zeit nicht im Einsatz.";

	else
	{
		foreach ($xml->einsaetze->einsatz as $einsatz)
		{    
			if(strcmp($einsatz->num1,$actionID) == 0)
			{
				echo "<b>Einsatz:</b><br/>";
				$startTime = DateTime::createFromFormat('D, d M Y H:i:s O', $einsatz->startzeit);
				
				$yesterday = "";
				$now = new DateTime();
				/*if($BrigadeStartTime < $now )
					$yesterday = "Gestern ";
				if($BrigadeStartTime->add(new DateInterval("P1D")) < $now)
					$yesterday = "Vorgestern ";
				if($BrigadeStartTime->add(new DateInterval("P2D")) < $now)
					$yesterday = $startTime->format("d.m.y")." "; 				
				*/
				echo " Die ".$FFName." ist seit: ".$yesterday.$BrigadeStartTime->format('H:i')." im Einsatz!<br/>";
				
				echo "<br/> Alarmstufe: ".htmlentities($einsatz->alarmstufe,ENT_IGNORE,"UTF-8");	
				echo "<br/>";
				//echo "Einsatzart: ";
				echo htmlentities($einsatz->einsatzsubtyp,ENT_IGNORE,"UTF-8");
				
				
				echo "<br/>Details: <a href='http://intranet.ooelfv.at/webext2/detail.php?NUM1=".$actionID."' target='_new'>OOeLfv</a>"; 	
			
				$long = $einsatz->lng;
				$lat = $einsatz->lat;
		
				echo "<br/>Einsatzort: <a href='http://maps.google.com/maps?f=q&hl=de&geocode=&q=".$lat.",".$long."' target='_new'> Karte</a>";
				
				echo "<br/>Adresse: ".htmlentities($einsatz->adresse->default,ENT_IGNORE,"UTF-8");
				if($einsatz->adresse->earea != "")
					echo "<br/>".htmlentities($einsatz->adresse->earea,ENT_IGNORE,"UTF-8");

				if($TargetFFId != '407219')
					echo "<br/>Powerd by <a href='http://zirking.at'>zirking.at</a>";	
			}
	}	}
}
?>
</p>
</body>
</html>