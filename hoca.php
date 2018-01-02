<?php
session_start();
?>
<style>

table, th, td {
   border: 1px solid black;
   width: 1000px;
   text-align:center; 
}

</style>

<?php
//include('ortak.php');
error_reporting(E_ALL ^ E_NOTICE);

$islem = $_GET['islem'];
echo $_SESSION['sid']. "-". $islem;
if($islem !='login')
	if(! isset($_SESSION['sid'] ))// login olmamıssa
		$islem = 'loginformu';
		
switch($islem){
	
	case 'hocaninProgrami':
		 hocaninProgrami($_GET['tid']);
		 
		 break;
	
	case 'loginformu':
		 loginformu();
		 
		 break;
		 
	case 'login': 
		login($_GET['kullaniciadi'],$_GET['sifre']);
		break;

	
	case 'dersinProgrami':
		 dersinProgrami($_GET['cid']);
		 
		 break;
		 
	case 'odaninProgrami':
		 odaninProgrami($_GET['rid']);
		 
		 break;
		 
	case 'dersprogrami':
		 dersProgrami($_GET['sid']);
	
		break;
		
	case 'ogrdersprogrami':
		 ogrDersProgrami($_GET['sid']);
	
		break;
	
	case 'guncelle':
		guncelle($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['dtarihi']);
		listele2();
		break;
	
	case 'derssec':
		 derssec($_GET['sid']);
		
	case 'derslistele':
		derslistele($_GET['bolumno'],$_GET['sid']);
		break;
	
	case 'sil':
        sil($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['dtarihi']);
        listele();
        break;
		
	case 'derssil':
        derssil($_GET['sid'],$_GET['cid']);
        break;
		
	case 'dersleriGoster':
		derslerigoster($_GET['id']);
		break;
		
	case 'dersekle':
		dersekle($_GET['sid'],$_GET['cid']);
		break;
		
    case 'ekle':
        ekle($_GET['no'],$_GET['ad'],$_GET['soyad'],$_GET['dtarihi'],$_GET['dyeri'],$_GET['deptid']); 	
	    listele2();
		break;
	
	case 'eklemeFormuGoster':
		eklemeFormuGoster();
		break;
		
	case 'guncellemeFormuGoster':
		guncellemeFormuGoster($_GET['id']);
	
		break;
	
	case 'listele':
		break;

	
	case 'ogrenciListesi':
		listele();
		break;
	
	case'teklistele':
		teklistele($_GET['sid']);
		break;
	
	case 'dersListesi':
		derssec();
		break;
	
	case 'cikis':
		cikis();
		break;
		
	default:
		loginformu();
}
	
function login($kullaniciadi,$sifre){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM student WHERE sid=$kullaniciadi AND password=$sifre LIMIT 1;"); //echo "SQL: $sql<br>";
	if(mysqli_num_rows($rowSet1)>0){
		$_SESSION['sid']=$kullaniciadi;
		$_SESSION['kullanicikaydi']=mysqli_fetch_assoc($rowSet1);
		
		listele2();
	}
	else{
		echo "Başarısız";
		loginformu();
	}
	
}
function loginformu(){
	echo "<form action=?>
	Kullanıcı Adı:<input type=text name=kullaniciadi></input>
	Şifre:<input type=password name=sifre></input>
	<input type=submit value=giriş></input>
	<input type=hidden name=islem value=login></input>
	</form>";
}

function dersinProgrami($cid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM course WHERE cid=$cid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['title']}'ın PROGRAMI</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM schedule s, course c, teach t, teacher te, room r WHERE c.cid=s.cid AND c.cid=$cid AND s.rid=r.rid AND s.cid=t.cid AND t.tid=te.tid;"); //echo "SQL: $sql<br>";
	//echo $sql;
	
	for($i=0;$i<5; $i++){
		for($j=0;$j<5; $j++){
			$hafta[$i][$j]['title']=' ';
			$hafta[$i][$j]['rdescription']=' ';
			
		}
	}
	
	while($kayit = mysqli_fetch_assoc($rowSet))
		$hafta[$kayit['hourOfDay']-1][$kayit['dayOfWeek']-1] 
			= array('title'=>$kayit['title'],'rdescription'=>$kayit['rdescription'],'cid'=>$kayit['cid'],'rid'=>$kayit['rid'],'fname'=>$kayit['fname'],'lname'=>$kayit['lname'],'tid'=>$kayit['tid']);
	//echo "<pre>"; print_r($hafta); echo "</pre>";
	
	
	
	echo "<table> <thead> <th>Saat</th> <th>Pzt</th> <th>Salı</th> <th>Çarş</th> <th>Perş</th> <th>Cuma</th>	</thead>
<tbody>";
	$i=9;
	foreach($hafta as $saat){
		
		echo "<tr><th>$i</th>
			<td><a href='?islem=dersinProgrami&cid={$saat[0]['cid']}'>{$saat[0]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[0]['rid']}'>{$saat[0]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[0]['tid']}'>{$saat[0]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[0]['tid']}'>{$saat[0]['lname']}</td>
			<td><a href='?islem=dersinProgrami&cid={$saat[1]['cid']}'>{$saat[1]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[1]['rid']}'>{$saat[1]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[1]['tid']}'>{$saat[1]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[1]['tid']}'>{$saat[1]['lname']}</td>
			<td><a href='?islem=dersinProgrami&cid={$saat[2]['cid']}'>{$saat[2]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[2]['rid']}'>{$saat[2]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[2]['tid']}'>{$saat[2]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[2]['tid']}'>{$saat[2]['lname']}</td>
			<td><a href='?islem=dersinProgrami&cid={$saat[3]['cid']}'>{$saat[3]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[3]['rid']}'>{$saat[3]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[3]['tid']}'>{$saat[3]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[3]['tid']}'>{$saat[3]['lname']}</td>
			<td><a href='?islem=dersinProgrami&cid={$saat[4]['cid']}'>{$saat[4]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[4]['rid']}'>{$saat[4]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[4]['tid']}'>{$saat[4]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[4]['tid']}'>{$saat[4]['lname']}</td>
			</tr>";
			$i++;
	}
	echo "</tbody></table>";
	mysqli_close($mysqli);	
}

function dersProgrami($sid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM student WHERE sid=$sid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['fname']} {$kayit1['lname']}'ın PROGRAMI</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * 
											FROM course c, take t, schedule s, room r 
											WHERE t.cid=s.cid AND r.rid=s.rid AND t.cid=c.cid AND t.sid=$sid;"); //echo "SQL: $sql<br>";
	//echo $sql;
	
	for($i=0;$i<5; $i++){
		for($j=0;$j<5; $j++){
			$hafta[$i][$j]['title']=' ';
			$hafta[$i][$j]['rdescription']=' ';
			
		}
	}
	
	while($kayit = mysqli_fetch_assoc($rowSet))
		$hafta[$kayit['hourOfDay']-1][$kayit['dayOfWeek']-1] 
			= array('title'=>$kayit['title'],'rdescription'=>$kayit['rdescription'],'cid'=>$kayit['cid'],'rid'=>$kayit['rid']);
	//echo "<pre>"; print_r($hafta); echo "</pre>";
	
	
	
	echo "<table> <thead> <th>Saat</th> <th>Pzt</th> <th>Salı</th> <th>Çarş</th> <th>Perş</th> <th>Cuma</th>	</thead>
<tbody>";
	$i=9;
	foreach($hafta as $saat){
		echo "
			print_r($saat[0]);
		<tr> <th>$i</th>
			<td><a href='?islem=dersinProgrami&cid={$saat[0]['cid']}'>{$saat[0]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[0]['rid']}'>{$saat[0]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[1]['cid']}'>{$saat[1]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[1]['rid']}'>{$saat[1]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[2]['cid']}'>{$saat[2]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[2]['rid']}'>{$saat[2]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[3]['cid']}'>{$saat[3]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[3]['rid']}'>{$saat[3]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[4]['cid']}'>{$saat[4]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[4]['rid']}'>{$saat[4]['rdescription']}</td> 
			</tr>";
			$i++;
	}
	echo "</tbody></table>";
	mysqli_close($mysqli);	
}

/*function ogrDersProgrami($sid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM student WHERE sid=$sid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['fname']} {$kayit1['lname']}'ın PROGRAMI</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * 
											FROM course c, take t, schedule s, room r 
											WHERE t.cid=s.cid AND r.rid=s.rid AND t.cid=c.cid AND t.sid=$sid;"); //echo "SQL: $sql<br>";
	//echo $sql;
	
	for($i=0;$i<5; $i++){
		for($j=0;$j<5; $j++){
			$hafta[$i][$j]['title']=' ';
			$hafta[$i][$j]['rdescription']=' ';
			
		}
	}
	
	while($kayit = mysqli_fetch_assoc($rowSet))
		$hafta[$kayit['hourOfDay']-1][$kayit['dayOfWeek']-1] 
			= array('title'=>$kayit['title'],'rdescription'=>$kayit['rdescription'],'cid'=>$kayit['cid'],'rid'=>$kayit['rid']);
	//echo "<pre>"; print_r($hafta); echo "</pre>";
	
	
	
	echo "<table> <thead> <th>Saat</th> <th>Pzt</th> <th>Salı</th> <th>Çarş</th> <th>Perş</th> <th>Cuma</th>	</thead>
<tbody>";
	$i=9;
	foreach($hafta as $saat){
		echo "<tr> <th>$i</th>
			<td>{$saat[0]['title']}-{$saat[0]['rdescription']}</td> 
			<td>{$saat[1]['title']}-{$saat[1]['rdescription']}</td> 
			<td>{$saat[2]['title']}-{$saat[2]['rdescription']}</td> 
			<td>{$saat[3]['title']}-{$saat[3]['rdescription']}</td> 
			<td>{$saat[4]['title']}-{$saat[4]['rdescription']}</td> 
			</tr>";
			$i++;
	}
	echo "</tbody></table>";
	mysqli_close($mysqli);	
}
*/
function odaninProgrami($rid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM room WHERE rid=$rid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['rdescription']}'ın PROGRAMI</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM  schedule p,course c,room r,teach,teacher WHERE r.rid=$rid AND r.rid=p.rid AND c.cid=p.cid AND c.cid=teach.cid AND teach.tid=teacher.tid;"); //echo "SQL: $sql<br>";
	//echo $sql;
	
	for($i=0;$i<5; $i++){
		for($j=0;$j<5; $j++){
			$hafta[$i][$j]['title']=' ';
			$hafta[$i][$j]['rdescription']=' ';
			
		}
	}
	
	while($kayit = mysqli_fetch_assoc($rowSet))
		$hafta[$kayit['hourOfDay']-1][$kayit['dayOfWeek']-1] 
			= array('title'=>$kayit['title'],'rdescription'=>$kayit['rdescription'],'cid'=>$kayit['cid'],'rid'=>$kayit['rid'],'fname'=>$kayit['fname'],'lname'=>$kayit['lname'],'tid'=>$kayit['tid']);
	//echo "<pre>"; print_r($hafta); echo "</pre>";
	
	
	
	echo "<table> <thead> <th>Saat</th> <th>Pzt</th> <th>Salı</th> <th>Çarş</th> <th>Perş</th> <th>Cuma</th>	</thead>
<tbody>";
	$i=9;
	foreach($hafta as $saat){
		echo "<tr><th>$i</th> 
			<td><a href='?islem=dersinProgrami&cid={$saat[0]['cid']}'>{$saat[0]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[0]['rid']}'>{$saat[0]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[0]['tid']}'>{$saat[0]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[0]['tid']}'>{$saat[0]['lname']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[1]['cid']}'>{$saat[1]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[1]['rid']}'>{$saat[1]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[1]['tid']}'>{$saat[1]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[1]['tid']}'>{$saat[1]['lname']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[2]['cid']}'>{$saat[2]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[2]['rid']}'>{$saat[2]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[2]['tid']}'>{$saat[2]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[2]['tid']}'>{$saat[2]['lname']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[3]['cid']}'>{$saat[3]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[3]['rid']}'>{$saat[3]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[3]['tid']}'>{$saat[3]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[3]['tid']}'>{$saat[3]['lname']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[4]['cid']}'>{$saat[4]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[4]['rid']}'>{$saat[4]['rdescription']}-<a href='?islem=hocaninProgrami&tid={$saat[4]['tid']}'>{$saat[4]['fname']}-<a href='?islem=hocaninProgrami&tid={$saat[4]['tid']}'>{$saat[4]['lname']}</td> 
			</tr>";
	$i++;
	}
	echo "</tbody></table>";
	mysqli_close($mysqli);	
}

function hocaninProgrami($tid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM teacher WHERE tid=$tid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['fname']} {$kayit1['lname']}'ın PROGRAMI</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * 
											FROM teacher,teach,schedule p,course c,room r
											WHERE r.rid=p.rid AND c.cid=p.cid AND c.cid=teach.cid AND teach.tid=teacher.tid AND teacher.tid=$tid ;"); //echo "SQL: $sql<br>";
	//echo $sql;
	
	for($i=0;$i<5; $i++){
		for($j=0;$j<5; $j++){
			$hafta[$i][$j]['title']=' ';
			$hafta[$i][$j]['rdescription']=' ';
			
		}
	}
	
	while($kayit = mysqli_fetch_assoc($rowSet))
		$hafta[$kayit['hourOfDay']-1][$kayit['dayOfWeek']-1] 
			= array('title'=>$kayit['title'],'rdescription'=>$kayit['rdescription'],'cid'=>$kayit['cid'],'rid'=>$kayit['rid']);
	//echo "<pre>"; print_r($hafta); echo "</pre>";
	
	
	
	echo "<table> <thead><th>Saat</th> <th>Pzt</th> <th>Salı</th> <th>Çarş</th> <th>Perş</th> <th>Cuma</th>	</thead>
<tbody>";
	$i=9;
	foreach($hafta as $saat){
		echo "<tr> <th>$i</th>
			<td><a href='?islem=dersinProgrami&cid={$saat[0]['cid']}'>{$saat[0]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[0]['rid']}'>{$saat[0]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[1]['cid']}'>{$saat[1]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[1]['rid']}'>{$saat[1]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[2]['cid']}'>{$saat[2]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[2]['rid']}'>{$saat[2]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[3]['cid']}'>{$saat[3]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[3]['rid']}'>{$saat[3]['rdescription']}</td> 
			<td><a href='?islem=dersinProgrami&cid={$saat[4]['cid']}'>{$saat[4]['title']}</a>-<a href='?islem=odaninProgrami&rid={$saat[4]['rid']}'>{$saat[4]['rdescription']}</td> 
			</tr>";
	$i++;
	}
	echo "</tbody></table>";
	mysqli_close($mysqli);	
}

function sil($no,$ad,$soyad,$dtarihi){
	$mysqli = new mysqli("localhost","root","","test");
    $sql = mysqli_query($mysqli,"DELETE FROM student WHERE sid=$no LIMIT 1;");
    if(mysqli_affected_rows($mysqli)>0)
	echo "Kayıt başarıyla silindi"; echo "<br>";
	$rowset=mysqli_query($mysqli,$sql);	
	mysqli_close($mysqli);
}

function ekle($sid,$fname,$lname,$birthdate,$birthplace,$did){
	$mysqli = new mysqli("localhost","root","","test");
    $sql = mysqli_query($mysqli,"INSERT INTO student VALUES($sid,'$fname','$lname','$birthdate','$birthplace',$did)");
    if(mysqli_affected_rows($mysqli)>0)
	echo "Kayıt başarıyla eklendi"; echo "<br>";
	$rowset=mysqli_query($mysqli,$sql);	
	mysqli_close($mysqli);

}
function eklemeFormuGoster(){
	echo "<form action=? method=get>
id <input type=text name=no value=''><br>
ad <input type=text name=ad value=' '> <br>
soyad <input type=text name=soyad value=''><br>
dtarihi <input type=text name=dtarihi value=''><br>
dyeri <input type=text name=dyeri value=''><br>
deptid <input type=text name=deptid value=''><br>
<input type=submit><br>
<input type=hidden name=islem value=ekle>
</form>";
}
function guncelle($no,$ad,$soyad,$dtarihi){
	$mysqli = new mysqli("localhost","root","","test");
	$sql = "UPDATE student SET fname='$ad',
	lname='$soyad',
	birthdate='$dtarihi'
	WHERE sid=$no";
	echo "SQL: $sql<br>";
	$rowset=mysqli_query($mysqli,$sql);
if(mysqli_affected_rows($mysqli)>0){
	echo "Kayıt başarıyla eklendi"; echo "<br>";
	echo "<a href=?islem=ogrenciBilgisi>Geriye dönmek için tıklayınız</a>";
	
}	
else
	echo "Kayıt ekleme başarısız";
    
mysqli_close($mysqli);
}	
function guncellemeFormuGoster($no){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM student WHERE sid=$no LIMIT 1;");
	echo "$sql <br>";
	$row = mysqli_fetch_assoc($rowSet);
	?>
	<form action=? method=get>
	no <input type=text name=no value=' <?php echo $row['sid']; ?>'> <br>
	ad <input type=text name=ad value=' <?php echo $row['fname']; ?>'> <br>
	soyad <input type=text name=soyad value=' <?php echo $row['lname']; ?>'><br>
	dTarihi <input type=text name=dtarihi value=' <?php echo $row['birthdate']; ?>'><br>
	<input type=submit value="Gönder"><br>
	<input type=reset value="Temizle"><br>
	<input type=hidden name=islem value="guncelle">
	<input type=hidden name=id value=' <?php echo $row['sid']; ?>' >
</form>
<?php
}

function anasayfa(){
		
		echo "<a href='?islem=eklemeFormuGoster'>Yeni kayıt eklemek için tıklayın.</a><br>";
}

function listele(){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM  student;");
	$dept = mysqli_query($mysqli, $sql = "SELECT department.dname FROM student, department WHERE student.did = department.did ORDER BY student.sid ;");
	
	
	echo "<table>
<thead> <th>No</th><th>Ad</th><th>Soyad</th><th>Departman</th></thead>
<tbody>";
	while($kayit = mysqli_fetch_assoc($rowSet)){
		 $dept1 = mysqli_fetch_assoc($dept);
		 echo "<td>{$kayit['sid']}</td> 
		 <td>{$kayit['fname']}</td> 	
		 <td>{$kayit['lname']}</td>
		 <td>{$dept1['dname']}</td>	 
		 </tr>";
}
    echo "</tbody></table>";
mysqli_close($mysqli);
}

function teklistele($sid){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM  student s WHERE s.sid=$sid ;");
	$dept = mysqli_query($mysqli, $sql = "SELECT department.dname FROM student, department WHERE student.did = department.did ORDER BY student.sid ;");
	echo "<table>
<thead> <th>No</th><th>Ad</th><th>Soyad</th><th>Departman</th><th>Doğum Tarihi</th><th>Doğum Yeri</th><th colspan=4 >Düzenle</th></thead>
<tbody>";
	while($kayit = mysqli_fetch_assoc($rowSet)){
		 $dept1 = mysqli_fetch_assoc($dept);
		 echo "<td>{$kayit['sid']}</td> 
		 <td>{$kayit['fname']}</td> 	
		 <td>{$kayit['lname']}</td>
		 <td>{$dept1['dname']}</td>	
		 <td>{$kayit['birthdate']}</td>
		 <td>{$kayit['birthplace']}</td>	

		 <td><a href='?islem=guncellemeFormuGoster&id={$kayit['sid']}'>Bilgilerimi Güncelle</a></td>

		 </tr>";
	}
}
function listele2(){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM  student;");
	$dept = mysqli_query($mysqli, $sql = "SELECT department.dname FROM student, department WHERE student.did = department.did ORDER BY student.sid ;");
	echo "<table>
<thead> <th>No</th><th>Ad</th><th>Soyad</th><th>Departman</th><th>Doğum Tarihi</th><th>Doğum Yeri</th><th colspan=4 >Düzenle</th></thead>
<tbody>";
	while($kayit = mysqli_fetch_assoc($rowSet)){
		 $dept1 = mysqli_fetch_assoc($dept);
		 echo "<td>{$kayit['sid']}</td> 
		 <td>{$kayit['fname']}</td> 	
		 <td>{$kayit['lname']}</td>
		 <td>{$dept1['dname']}</td>	
		 <td>{$kayit['birthdate']}</td>
		 <td>{$kayit['birthplace']}</td>	

		 <td><a href='?islem=guncellemeFormuGoster&id={$kayit['sid']}'>değiştir</a></td>
		 <td><a href='?islem=sil&no={$kayit['sid']}'>sil</a></td>
		 <td><a href='?islem=dersleriGoster&id={$kayit['sid']}'>dersler</a></td>
		 <td><a href='?islem=dersprogrami&sid={$kayit['sid']}'>dersprogrami</a></td>

		 </tr>";
}
    echo "</tbody></table>";
mysqli_close($mysqli);
}
function derslistele($did,$sid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM department WHERE did=$did;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['dname']}'ın DERSLERİ</h2>";
	
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM course where did=$did;");
	echo "<table>
		<thead> <th>cid</th><th>ad</th><th>description</th><th>kredi</th></thead>
		<tbody>";
	while($kayit = mysqli_fetch_assoc($rowSet)){
		 echo "<td>{$kayit['cid']}</td> 
		 <td>{$kayit['title']}</td> 	
		 <td>{$kayit['description']}</td>
		 <td>{$kayit['credits']}</td>
		  <td><a href='?islem=dersekle&sid={$sid}&cid={$kayit['cid']}'>Ders ekle</a></td>
		 </tr>";
	}
    echo "</tbody></table>";
	mysqli_close($mysqli);
}

function derssec($sid){
	
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet = mysqli_query($mysqli, $sql = "SELECT * FROM  department;");
	echo "<form method=get>
	<select name=bolumno>";
	while($row = mysqli_fetch_assoc($rowSet)){
			echo "<option value={$row['did']}>{$row['dname']}</option>";
	}
	echo "</select>
	<input type=submit name=buton value='Dersleri Goster'>
	<input type=hidden name=islem value=derslistele>
	<input type=hidden name=sid value=$sid>

	</form>";
}

function dersekle($sid,$cid){
	$mysqli = new mysqli("localhost","root","","test");
    $sql = mysqli_query($mysqli,"INSERT INTO take VALUES('$sid','$cid',0)");
	if(! $sql)
		echo mysqli_error(mysqli);
    if(mysqli_affected_rows($mysqli)>0){
	echo "Kayıt başarıyla eklendi"; echo "<br>";
	derslerigoster($sid);
	}
	else{
	echo "eklenemedi";
	}
	$rowSet=mysqli_query($mysqli,$sql);	
	mysqli_close($mysqli);
}

function derslerigoster($sid){
	$mysqli = new mysqli("localhost","root","","test");
	$rowSet1 = mysqli_query($mysqli, $sql1 = "SELECT * FROM student WHERE sid=$sid;"); //echo "SQL: $sql<br>";
	$kayit1 = mysqli_fetch_assoc($rowSet1);
	echo "<h2>{$kayit1['fname']} {$kayit1['lname']}'ın DERSLERİ</h2>";
	$rowSet = mysqli_query($mysqli, $sql = "SELECT take.sid,course.cid, course.title, course.description, course.credits,course.did FROM take, course WHERE take.sid=$sid and take.cid= course.cid;");
	echo "<table>
		<thead> <th>cid</th><th>ad</th><th>description</th><th>kredi</th></thead>
		<tbody>";
	while($kayit = mysqli_fetch_assoc($rowSet)){
		 echo "<tr><td>{$kayit['did']}</td> 
		 <td>{$kayit['title']}</td> 	
		 <td>{$kayit['description']}</td>
		 <td>{$kayit['credits']}</td>
		 <td><a href='?islem=derssil&sid={$kayit['sid']}&cid={$kayit['cid']}'>sil</a></td>
		 <td><a href='?islem=derssec&sid={$kayit['sid']}'>yeni</a></td>

		 </tr>";
	}
	
    echo "</tbody></table>";

	mysqli_close($mysqli);
}

function derssil($sid, $cid){
	$mysqli = new mysqli("localhost","root","","test");
    $sql = mysqli_query($mysqli,"DELETE FROM take WHERE take.sid=$sid and take.cid=$cid;");
    if(mysqli_affected_rows($mysqli)>0)
	echo "Kayıt başarıyla silindi"; echo "<br>";
	$rowset=mysqli_query($mysqli,$sql);	
	mysqli_close($mysqli);
}

function cikis(){
	
	session_destroy();
	header("Location:giris2.php");
}

?>
