<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>JSON TEST</title>
<meta http-equiv="refresh" content="10" />
	<script>
	var timeleft = 10;
	var downloadTimer = setInterval(function(){
	  document.getElementById("progressBar").value = 10 - --timeleft;
	  if(timeleft <= 0)
		clearInterval(downloadTimer);
	},1000);
	</script>
	<style>
	body {
	  font: 13px/1.3 'Lucida Grande',sans-serif;
	  color: #666;
	}

	.chart {
	  display: table;
	  table-layout: fixed;
	  width: 60%;
	  max-width: 700px;
	  height: 200px;
	  margin: 0 auto;
	  background-image: linear-gradient(to top, rgba(0, 0, 0, 0.1) 2%, rgba(0, 0, 0, 0) 2%);
	  background-size: 100% 50px;
	  background-position: left top;
	}
	.durum 
	{
	  display: table;
	  table-layout: fixed;
	  width: 60%;
	  max-width: 700px;
	  height: 200px;
	  margin: 0 auto;
		}
	.buton
		{
				text-decoration: none;
		padding: 20px;
		background: crimson;
		width: 100%;
		display: block;
		border-radius: 10px;
		color: white;
		font-size: 16px;
		}
	.chart li {
	  position: relative;
	  display: table-cell;
	  vertical-align: bottom;
	  height: 200px;
	}
	.chart span {
	  margin: 0 1em;
	  display: block;
	  background: rgba(209, 236, 250, 0.75);
	  animation: draw 1s ease-in-out;
	}
	.chart span:before {
	  position: absolute;
	  left: 0;
	  right: 0;
	  top: 100%;
	  padding: 5px 1em 0;
	  display: block;
	  text-align: center;
	  content: attr(title);
	  word-wrap: break-word;
	}

	@keyframes draw {
	  0% {
		height: 0;
	  }
	}
	</style>
</head>

<body>

<?php
date_default_timezone_set('Europe/Istanbul');
//Veri Tabanı İşlemleri
$mysql_hostname = "localhost"; // HOSTNAME
$mysql_user = "root"; //DATABASE USERNAME
$mysql_password = "rootroot"; //DATABASE USER PASSWORD
$mysql_database = "datajson"; //DATABASE NAME
$url = 'HTTP://WWW.TESTSITE.COM/data.json'; // JSON Yolu
	
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("MYSQL'e baglanamadi");
mysql_select_db($mysql_database, $bd) or die("Veri tabanina baglanamadi");
//Türkçe Karakter Ayarlamaları (Sadece Önlem için)
mysql_query('SET NAMES UTF8'); 
mysql_query("SET CHARACTER SET utf8"); 
mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'"); 

//EKSTRA
$g = mysql_query("SELECT * FROM days WHERE datee='".date("d-m-Y")."' ");
$gvarmi = mysql_num_rows($g);
if($gvarmi>0)
{
	
}else 
{
	$gekle = "INSERT INTO days (`id` ,`datee` ,`say`)
			  VALUES (NULL ,  '".date("d-m-Y")."',  '0');";
	mysql_query($gekle);
}


// Data Okuma

$data = file_get_contents($url); // Dosyaları Oku
$characters = json_decode($data); // Parçala Behçet


$vormis=0;
$eklenen=0;

//Döndür
foreach ($characters as $character) {
	// Önce kaydı çek
	$s = mysql_query("SELECT * FROM datas WHERE
	name='".$character->name."' AND  surname='".$character->surname."' AND  sex='".$character->sex."' AND  phone='".$character->phone."' AND  city='".$character->city."'");
	// varmı yokmu kontrolü
	$vormi = mysql_num_rows($s);
	
	//varsa
	if($vormi>0)
	{
		$vormis++;
	}else //yoksa
	{
		//ekle işlemleri
		$sql = "INSERT INTO datas (`id` ,`name` ,`surname` ,`sex` ,`phone` ,`city`)
				VALUES (NULL ,  '".$character->name."',  '".$character->surname."',  '".$character->sex."',  '".$character->phone."',  '".$character->city."');";
		mysql_query($sql);
		$eklenen++;
		
		//rapor için günü arttır.
		$gart = "UPDATE days SET say = say+1  WHERE  datee='".date("d-m-Y")."' LIMIT 1 ;";
		mysql_query($gart);
		
	}
}
// mutlu son
?>
	<div class="durum">
		<div style="float: left; width: 50%;" align="center">
			<h2 align="center">Zaten Mevcut Kayıt Sayısı</h2>
			<h2 align="center"><?=$vormis?></h2>
		</div>
		<div style="float: left; width: 50%;" align="center">
			<h2 align="center">Eklenen Kayıt Sayısı</h2>
			<h2 align="center"><?=$eklenen?></h2>
		</div>
		<div style="float: left; width: 100%;" align="center">
			
			<a href="jsontest.php" class="buton"> <progress value="0" max="10" id="progressBar"></progress> <br><strong>Güncelle</strong></a>
		</div>
	</div>
<ul class="chart">
  <?php
	$sorgu1 = mysql_query("SELECT * FROM days LIMIT 30");
	while($tablo1 = mysql_fetch_array($sorgu1))
	{
  ?>
  <li>
    <span style="height:<?=$tablo1["say"]?>%" title="<?=$tablo1["datee"]?> (<?=$tablo1["say"]?> kayıt eklendi)"></span>
  </li>
  <?php } ?>
</ul>

</body>
</html>
