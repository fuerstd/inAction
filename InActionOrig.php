<html>
<head><title>Im Einsatz?</title></head>
<?php
	$xml = simplexml_load_file('http://intranet.ooelfv.at/webext2/rss/webext2_laufend.xml');

	$TargetFFId = '407219';
	if(isset($_POST['FFNr']))
		$TargetFFId = $_POST['FFNr'];

	$FFName=NULL;
	foreach ($xml->resources->resource as $resource)
	{    
		if($resource->attributes()->id == $TargetFFId)
		{
			$FFName = $resource->attributes()->name;
			echo "<br/>Resource-Name: ".$FFName;	
			echo "<br/>Resource-www: ".$resource->www;
			$actionID = $resource->usedat->attributes()->id;
 			echo "<br/>EinsatzID:".$actionID;
		}
	}


?>
<body>
Im Einsatz: <b>
<?php 
if(is_null($FFName))
	echo "Nein:".$TargetFFId."</b>";
else
{
	echo "Ja: ".$FFName;	

	echo "</b><br/>";
 
	echo "<br/>Titel: ".$xml->titel;
	echo "<br/>Zeitpunkt: ".$xml->pubDate;

	echo "<br/>Scope: ".$xml->scope;

	foreach ($xml->einsaetze->einsatz as $einsatz)
	{    
		if(strcmp($einsatz->num1,$actionID) == 0)
		{
		
			echo "<br/><b>FF ".$FFName ." im Einsatz!!:".$einsatz->num1."</b>";
			echo "<br/>Beginn:".$einsatz->startzeit;
			echo "<br/>Status:".$einsatz->status;
			echo "<br/>Einsatzart:".$einsatz->eisatzart;
			echo "<br/>Alarmstufe:".$einsatz->alarmstufe;
			echo "<br/>Einsatztyp:".$einsatz->einsatztyp;
			echo "<br/>Einsatztyp:".$einsatz->einsatztyp;
			echo "<br/>Adresse:".$einsatz->adresse->default;
			$long = $einsatz->lng;
			$lat = $einsatz->lat;
			echo "<br/>Einsatz  auf Karte: <a href='http://maps.google.com/maps?f=q&hl=de&geocode=&q=".$lat.",".$long."' target='_new'> Karte</a>";
		}
	}
}
?>
<form action="InAction.php" method="POST" >
FF NR:<input type="Text" name="FFNr" value="<?php echo $TargetFFId;?>" />
<input type="Submit" name="submit" onClick="submit">
</form>
</body>
</html>