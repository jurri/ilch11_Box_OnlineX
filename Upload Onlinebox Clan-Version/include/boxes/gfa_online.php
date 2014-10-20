<font color="#ffffff"><u><strong>Wer war/ist Online?</strong></u></font><br><br>
<script language="JavaScript" type="text/javascript">
 <!--
  function einblenden(div) {
   with(document.getElementById(div).style){
    if(display=="none"){
     display="inline";
     }
     else{
      display="none";
     }
    }
   }
  //-->
</script>
      
<?php
       
if (!defined('main')){die("no direct access");}
    
// Farben & RangIcon
$farbeAdmin         = '#fd0111'; $iconAdmin         = '<img src="include/images/onlineboxicons/admin.png" border="0" alt="Admin" style="vertical-align:text-bottom;"/>';  // Admin
$farbeCoAdmin       = '#fd8301'; $iconCoAdmin       = '<img src="include/images/onlineboxicons/coadmin.png" border="0" alt="CoAdmin" style="vertical-align:text-bottom;"/>'; // CoAdmin
$farbeWebmaster     = '#fdf401'; $iconWebmaster     = '<img src="include/images/onlineboxicons/webmaster.png" border="0" alt="Webmaster" style="vertical-align:text-bottom;"/>'; // Webmaster
$farbeLeader        = '#00ff00'; $iconLeader        = '<img src="include/images/onlineboxicons/leader.png" border="0" alt="Leader" style="vertical-align:text-bottom;"/>';  // Leader
$farbeCoLeader      = '#00ffa2'; $iconCoLeader      = '<img src="include/images/onlineboxicons/coleader.png" border="0" alt="CoLeader" style="vertical-align:text-bottom;"/>'; // CoLeader
$farbeMember        = '#01d1fe'; $iconMember        = '<img src="include/images/onlineboxicons/member.png" border="0" alt="Member" style="vertical-align:text-bottom;"/>';  // Member
$farbeTrialmember   = '#0006ff'; $iconTrialmember   = '<img src="include/images/onlineboxicons/trialmember.png" border="0" alt="Trialmember" style="vertical-align:text-bottom;"/>';  // Trialmember
$farbeFreunde       = '#9c00ff'; $iconFreunde       = '<img src="include/images/onlineboxicons/freunde.png" border="0" alt="Freunde" style="vertical-align:text-bottom;"/>'; // Freunde
$farbeUser          = '#ffffff'; $iconUser          = '<img src="include/images/onlineboxicons/user.png" border="0" alt="User" style="vertical-align:text-bottom;"/>';  // User
$farbeGast          = '#b0afb1'; $iconGast          = '<img src="include/images/onlineboxicons/gast.png" border="0" alt="Gast" style="vertical-align:text-bottom;"/>';  // Gast
       
$dif = date('Y-m-d H:i:s', time() - 60);
$abf = "SELECT a.uid, b.avatar, b.recht FROM `prefix_online` a LEFT JOIN prefix_user b ON a.uid = b.id WHERE uptime > '". $dif."'";
$resultID = db_query($abf);
$brk='';
$uid = array();
$guests = 0;
$guestn = $lang['guests'];
$content='';
       
       
while($row = db_fetch_object($resultID)){     
    if(file_exists($row->avatar)){
        $avatar = '<a href="index.php?user-details-'.$row->uid.'"><img width="40" height="53" src="'.$row->avatar.'" border="0">';
    }else{
        $avatar = '<a href="index.php?user-details-'.$row->uid.'"><img width="40" height="53" src="include/images/avatars/noavatar.jpg" border="0">';
    }
	
    if($row->uid != 0 AND $brk!=$row->uid){
        $name=@db_result(db_query('SELECT name FROM prefix_user WHERE id='.$row->uid),0);
    
		if      ($row->recht == -9) {$farbe = $farbeAdmin; $rangIcon = $iconAdmin;}
		elseif  ($row->recht == -8) {$farbe = $farbeCoAdmin; $rangIcon = $iconCoAdmin;}
		elseif  ($row->recht == -7) {$farbe = $farbeWebmaster; $rangIcon = $iconWebmaster;}
		elseif  ($row->recht == -6) {$farbe = $farbeLeader; $rangIcon = $iconLeader;}
		elseif  ($row->recht == -5) {$farbe = $farbeCoLeader; $rangIcon = $iconCoLeader;}
		elseif  ($row->recht == -4) {$farbe = $farbeMember; $rangIcon = $iconMember;}
		elseif  ($row->recht == -3) {$farbe = $farbeTrialmember; $rangIcon = $iconTrialmember;}
		elseif  ($row->recht == -2) {$farbe = $farbeFreunde; $rangIcon = $iconFreunde;}
		elseif  ($row->recht == -1) {$farbe = $farbeUser; $rangIcon = $iconUser;}
		elseif  ($row->recht == -0) {$farbe = $farbeGast; $rangIcon = $iconGast;}
					   
		$content.='<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0">
				   <tr>
				   <td width="5%" align="center"><img src="include/images/icons/online.gif" border="0" alt="online"></td>
				   <td width="80%" align="left" ><a class="box" onmouseover="javascript:einblenden('.$row->uid.')" onmouseout="javascript:einblenden('.$row->uid.')" href="index.php?user-details-'.$row->uid.'"><font style="color:'.$farbe.'">'.$name.'</font></a></td>
				   <td width="5%" align="center">'.$rangIcon.'</td>
				   </tr></table>
				   <div id="'.$row->uid.'" style="display : none;">
				   <table align="center" border="0" width="90%" cellspacing="2" cellpadding="0" style="border: 1px solid #00ff00">
				   <tr>
				   <td>'.$avatar.'</td>
				   <td><font color="#00ff00">Jetzt Online!</font></td>
				   </tr>
				   </table></div>'."\n";
		$uid[] = $row->uid;
    }
	
    if($row->uid == 0){ 
		$guests++; 
	}
    $brk=$row->uid;
}

if($guests == 1){ 
	$guestn = $lang['guest']; 
}

if(empty($content)){
	$content.='<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0"><tr><td><img src="include/images/icons/offline.gif"  border="0" alt="offline"><font color="#003366">0 User </font></td></tr></table>'."\n"; 
}
       
$content.='<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0"><tr><td colspan="2"><hr style="height: 0px; border: dashed #9AB1C8 0px; border-top-width: 1px;"></td></tr></table>'."\n";
$where = (count($uid)>0) ? 'WHERE id NOT IN ('.implode(', ', $uid).')' : '';
$abf2 = 'SELECT * FROM prefix_user '.$where.' ORDER BY llogin DESC LIMIT 0,7';
$erg2 = db_query($abf2);
       
while($row2 = db_fetch_object($erg2)){
    if      ($row2->recht == -9) {$farbe = $farbeAdmin; $rangIcon = $iconAdmin;}
    elseif  ($row2->recht == -8) {$farbe = $farbeCoAdmin; $rangIcon = $iconCoAdmin;}
    elseif  ($row2->recht == -7) {$farbe = $farbeWebmaster; $rangIcon = $iconWebmaster;}
    elseif  ($row2->recht == -6) {$farbe = $farbeLeader; $rangIcon = $iconLeader;}
    elseif  ($row2->recht == -5) {$farbe = $farbeCoLeader; $rangIcon = $iconLeader;}
    elseif  ($row2->recht == -4) {$farbe = $farbeMember; $rangIcon = $iconMember;}
    elseif  ($row2->recht == -3) {$farbe = $farbeTrialmember; $rangIcon = $iconTrialmember;}
    elseif  ($row2->recht == -2) {$farbe = $farbeFreunde; $rangIcon = $iconFreunde;}
    elseif  ($row2->recht == -1) {$farbe = $farbeUser; $rangIcon = $iconUser;}
    elseif  ($row2->recht == -0) {$farbe = $farbeGast; $rangIcon = $iconGast;}
    
    if(file_exists($row2->avatar)){
        $avatar = '<a href="index.php?user-details-'.$row2->id.'"><img width="40" height="53" src="'.$row2->avatar.'" border="0">';
    }else{
        $avatar = '<a href="index.php?user-details-'.$row2->id.'"><img witdh="40" height="53" src="include/images/avatars/noavatar.jpg" border="0">';
    }
    
    $datum = date('H:i \U\h\r - d.m.y',$row2->llogin);
    $content.='<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0">
               <tr>
               <td align="center" width="5%"><img src="include/images/icons/offline.gif"  border="0" alt="offline"></td>
               <td align="left" width="80%"><a class="box" onmouseover="javascript:einblenden('.$row2->id.')" onmouseout="javascript:einblenden('.$row2->id.')" href="index.php?user-details-'.$row2->id.'"><font style="color:'.$farbe.'">'.$row2->name.'</font></a></td>
               <td align="center" width="5%">'.$rangIcon.'</td>
               </tr>
               </table>
               <div id="'.$row2->id.'" style="display : none;">
               <table align="center" border="0" width="90%" cellspacing="2" cellpadding="0" style="border: 1px solid #FF0000">
               <tr><td>'.$avatar.'</td><td>&nbsp;Letztes mal Online:<br /> '.$datum.'</td></tr>
               </table></div>'."\n";
}

if($guests == 0){
    $content.= '<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0"><tr><td colspan="2"><hr style="height: 0px; border: dashed #9AB1C8 0px; border-top-width: 1px";></td></tr>'."\n".'
                <tr><td><img src="include/images/icons/offline.gif"  border="0" alt="offline"><font size="-1" color="'.$farbeGast.'"> 0 '.$lang['guests'].'</td></tr></table>'."\n";
}else{
    $content.= '<table width="90%" align="center" cellpadding="2" cellspacing="0" border="0"><tr><td colspan="2"><hr style="height: 0px; border: dashed #9AB1C8 0px; border-top-width: 1px;"></td></tr>'."\n".'
                <tr><td><img src="include/images/icons/online.gif" border="0" alt="online"><font size=-1> '.$guests.' '.$guestn.'</font></td></tr></table>'."\n";
}

echo $content; 
?>     
<font size="1"><br></font>
<script type="text/javascript" language="javascript">
	function Klappen(Id){
		var KlappText = document.getElementById('Lay'+Id);
		var KlappBild = document.getElementById('Pic'+Id);
		var jetec_Minus="MINUS GRAFIK", jetec_Plus="PLUS GRAFIK";
		if(KlappText.style.display == 'none'){
			KlappText.style.display = 'block';
			KlappBild.src = jetec_Minus;
		}else{
			KlappText.style.display = 'none';
			KlappBild.src = jetec_Plus;
		}
	}
</script>

<a href="javascript:Klappen(0)"><font color="#ffffff">Aufklappen</font></a>

<div id="Lay0" style="display: none;">
	<div align="center"><font size="1"><br></font>
		<table width="95%">
		<td align="center" height="4">
			<fieldset><legend><b><font color="#ffffff">Clan-Legende</font></b></legend>
			<br><font color="#ffffff"><u><strong>Legende/Statistik</strong></u></font>
			<div class="toggle_ce">
				<div class="visiblebox"> 
					<div align="left"><blockquote style="margin-left:40px;"><br><b>
						<img src="include/images/onlineboxicons/admin.png" width="16" height="16" align="top"/> <font color="#fd0111"> Admin </font><br><br>
						<img src="include/images/onlineboxicons/coadmin.png" width="16" height="16" align="top"/> <font color="#fd8301"> Co-Admin </font><br><br>
						<img src="include/images/onlineboxicons/webmaster.png" width="16" height="16" align="top"/> <font color="#fdf401"> Webmaster </font><br><br>
						<img src="include/images/onlineboxicons/leader.png" width="16" height="16" align="top"/> <font color="#00ff00"> Leader </font><br><br>
						<img src="include/images/onlineboxicons/coleader.png" width="16" height="16" align="top"/> <font color="#00ffa2"> Co-Leader </font><br><br>
						<img src="include/images/onlineboxicons/member.png" width="16" height="16" align="top"/> <font color="#01d1fe"> Member </font><br><br>
						<img src="include/images/onlineboxicons/trialmember.png" width="16" height="16" align="top"/> <font color="#0006ff"> Trialmember </font><br><br>
						<img src="include/images/onlineboxicons/freunde.png" width="16" height="16" align="top"/> <font color="#9c00ff"> Freunde </font><br><br>
						<img src="include/images/onlineboxicons/user.png" width="16" height="16" align="top"/> <font color="#ffffff"> User </font><br><br>
						<img src="include/images/onlineboxicons/gast.png" width="16" height="16" align="top"/> <font color="#b0afb1"> Gast </font></b></blockquote>
					</div>
				</div>
				<div class="togglebox"></div>
				<div class="toggler"><br>
<?php
 # Copyright by Manuel Staechele
 # Support www.ilch.de
 
defined ('main') or die ( 'no direct access' );

if(empty($_GET['sum'])){
 
	$heute = date ('Y-m-d');
	$bges = @db_count_query("SELECT COUNT(*) FROM prefix_gallery_imgs");
	$ges_visits = db_result(db_query("SELECT SUM(count) FROM prefix_counter"),0);
	$ges_heute = @db_result(db_query("SELECT count FROM prefix_counter WHERE date = '".$heute."'"),0);
	$ges_gestern = @db_result(db_query('SELECT count FROM prefix_counter WHERE date < "'.$heute.'" ORDER BY date DESC LIMIT  1'),0);
	$gbook = @db_result(db_query("SELECT count(ID) FROM prefix_gbook"),0);
	$posts = @db_result(db_query("SELECT count(ID) FROM prefix_posts"),0);
	$topic = @db_result(db_query("SELECT count(ID) FROM prefix_topics"),0);
	$gesuser = @db_result(db_query("SELECT count(ID) FROM prefix_user"),0);

	echo '<table>
	<tr><td><strong><u>Besucher</u></strong></td></tr>
	<tr><td>Online:</td><td>'.ges_online().'</td></tr>
	<tr><td>'.$lang['whole'].':</td><td>'.$ges_visits.'</td></tr>
	<tr><td>'.$lang['today'].':</td><td>'.$ges_heute.'</td></tr>
 
	<tr><td>'.$lang['yesterday'].':</td><td>'.$ges_gestern.'</td></tr>
	<tr><td>Mitglieder:</td><td>'.$gesuser.'</td></tr>
	<tr><td> </td></tr>
	<tr><td><strong><u>Beiträge</u></strong></td></tr>
	<tr><td>Gästebuch:</td><td>'.$gbook.'</td></tr>
	<tr><td>Forum Posts:</td><td>'.$posts.'</td></tr>
	<tr><td>Forum Themen:</td><td>'.$topic.'</td></tr>
	<tr><td> </td></tr>
	<tr><td><a class="box" href="index.php?statistik"><b>... '.$lang['more'].'</b></a></td><td></td></table>';
}else{
 
	$title = $allgAr['title'].' :: Statistik';
	$hmenu = 'Statistik';
	$design = new design ( $title , $hmenu , 0 );
	$design->header();
	$anzahlShownTage = 7;
 
	echo '<table width="90%" align="center" class="border" cellpadding="0" cellspacing="1" border="0"><tr><td>';
	echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">';
	echo '<tr class="Chead"><td colspan="3" align="center"><b>Site Statistik</b></td></tr>';
 
	$max_in = 0;
	$ges = 0;
	$dat = array();
	$max_width = 200;
 
	$maxErg = db_query('SELECT MAX(count) FROM `prefix_counter`');
	$max_in = db_result($maxErg,0);
 
	$erg = db_query("SELECT count, DATE_FORMAT(date,'%a der %d. %b') as datum FROM `prefix_counter` ORDER BY date DESC LIMIT ".$anzahlShownTage);
	
	while($row = db_fetch_row($erg)){
 
		$value = $row[0];
 
		if(empty($value)){
			$bwidth = 0;
		}else{
			$bwidth = $value/$max_in * $max_width;
			$bwidth = round($bwidth,0);
		}
 
		echo '<tr class="Cnorm">';
		echo '<td>'.$row[1].'</td>';
		echo '<td><table width="'.$bwidth.'" border="0" cellpadding="0" cellspacing="0">';
		echo '<tr><td height="2" class="border"></td></tr></table>';
		echo '</td><td align="right">'.$value.'</td></tr>';
		$ges += $value;
	}

	$gesBesucher = db_query('SELECT SUM(count) FROM prefix_counter');
	$gesBesucher = @db_result($gesBesucher,0);
 
	echo '<tr class="Cmite"><td colspan="3"><div align="right">';
	echo 'Wochen Summe: '.$ges.'</div>';
	echo 'Besucher Gesamt '.$gesBesucher.' &nbsp; Maximal '.$max_in.'<br /><br />';
	echo '</td></tr><tr class="Cdark">';
	echo '<td colspan="3" align="center">[ <a href="javasript:window.close()">Fenster Schliesen</a> ]</td>';
	echo '</tr></table></td></tr></table><br />';
 
	$design->footer();
}
?>
 
<font color="#ffffff"><u><strong>Registed</strong></u></font>
 
<?php
 //Copyright by Stefan Jungbauer
 //www.zocker-eppingen.de
 //V 1.1
 
defined('main') or die('no direct access');
 
$erg = db_query("SELECT id, name, regist, geschlecht, gebdatum FROM prefix_user ORDER BY regist DESC LIMIT 3");
 
$content3.='<table>';
 
while($row = db_fetch_object($erg)){
 
	$geb = $row->gebdatum;
	$a = explode('-', $geb);
	$endung = $a[count($a) - 1];
	$tag = $a[2];
	$mon = $a[1];
	$jah = $a[0];
	$jetzt = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$gebur = mktime(0,0,0,$mon,$tag,$jah);
	$age = intval(($jetzt - $gebur) / (3600 * 24 * 365));
	$datum = date('d.m.y',$row->regist);
 
	if($row->geschlecht == 0){
		if($row->gebdatum != 0000-00-00 ){
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' ('.$age.') '.$row->name.'</a></td></tr>';
		}else{
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' '.$row->name.'</a></td></tr>'."\n";
		}
	}elseif($row->geschlecht == 1){
		if($row->gebdatum != 0000-00-00 ){
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' (M-'.$age.') '.$row->name.'</a></td></tr>';
		}else{
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' (M) '.$row->name.'</a></td></tr>';
		}
	}elseif($row->geschlecht == 2){
		if($row->gebdatum != 0000-00-00 ){
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' (W-'.$age.') '.$row->name.'</a></td></tr>';
		}else{
			$content3.='<tr><td><a href="?user-details-'.$row->id.'">'.$datum.' (W) '.$row->name.'</a></td></tr>';
		}
	}
}
$content3.='</table></div></div></fieldset></td></table></div></div>';

echo $content3;
?>
 



