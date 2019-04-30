<?php
	// include '../koneksi.php';
	// include '../session.php';
	include 'header.php';

	//Memanggil class aes
  include 'class/aes.class.php';
  include 'class/aesctr.class.php';

  //Memanggil class huffman
	// include 'class/huffmancoding.php';

  //Fungsi Proses Dekripsi RC4
	$pass = $_POST["katakunci"];
	$namafile = $_FILES["file"]["name"];
	function setupkey(){
		error_reporting(E_ALL ^ (E_NOTICE));
		$pass = $_POST["katakunci"];
		//echo "<br>";
		for($i=0;$i<256;$i++){
			$key[$i]=ord($pass[$i % strlen($pass)]); /*rubah ASCII ke desimal*/
		}
		global $mm;
		$mm=array();
		/*buat decrypt*/
		for($i=0;$i<256;$i++){
			$mm[$i] = $i;
		}
		$j = 0;
		$i = 0;
		for($i=0;$i<256;$i++){
			$a = $mm[$i];
			$j = ($j + $a + $key[$i]) % 256;
			$mm[$i] = $mm[$j];
			$mm[$j] = $a;
		}
	} /*akhir function*/

	function decrypt2($chipertext){
		global $mm;
		$xx=0;$yy=0;
		$plain='';
		for($n=1;$n<= strlen($chipertext);$n++){
			$xx = ($xx+1) % 256;
			$a = $mm[$xx];
			$yy = ($yy+$a) % 256;
			$mm[$xx] = $b = $mm[$yy];
			$mm[$yy] = $a;
			/*proses XOR antara chipertext dengan kunci
			dengan $chipertext sebagai chipertext
			dan $mm sebagai kunci*/
			$plain = ($chipertext^$mm[($a+$b) % 256]) % 256;
			return $plain;
		}
	}

  ini_set('memory_limit', '-1');
	ini_set('max_execution_time', '-1');

  $timer = microtime(true);

  $pw = $_POST['kunci'];
  $pt = $_FILES['file']['name'];
  $kcf = $_POST["katakunci"];
	$uploaded_name = $_FILES['file']['name'];
  $uploaded_ext = substr($uploaded_name, strrpos($uploaded_name, '.') + 1);
	$uploaded_size = $_FILES["file"]["size"];
	$dta = $_FILES["file"]["type"];

  $plain = empty($_POST['plain']) ? '' : $_POST['plain'];
  $decr = empty($_POST['decr']) ? $plain : AesCtr::decrypt($cipher, $pw, 256);

  function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }
  $time_start = microtime_float();

  if ($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])){
  	$pt = file_get_contents($_FILES['file']['tmp_name']);

  	// $dekomp = HuffmanCoding::decode($pt);

    setupkey();
  	$nmfile =  "hasil/$namafile";
  	/*ambil data dari file enkripsifile*/
  	$fp = fopen($nmfile, "r");
  	$isi = fread($fp,filesize($nmfile));
  	$go = $isi;
  	$key = $kcf;

  	// Algoritma Dekripsi RC4
  	for($i=0;$i<strlen($go);$i++){
  		$b[$i]=ord($go[$i]); /*rubah ASCII ke desimal*/
  		$d[$i]=decrypt2($b[$i]); /*proses dekripsi RC4*/
  		$s[$i]=chr($d[$i]); /*rubah desimal ke ASCII*/
  	}
  	$hsl='';
  	//Hasil Dekripsi
  	for($i=0;$i<strlen($go);$i++){
  		$hsl = $hsl . $s[$i];
  	}

  	$plain = AesCtr::decrypt($hsl, $pw, 256);

  	if(strlen($pw)<8){
  		echo "<script>alert('Password Kurang dari 8 Karakter');window.location='dekrip.php';</script>";
   			return;
  	}
    if($_FILES["file"]["error"] != 0){
  		echo "<script>alert('Tidak ada file enkrip yang diupload!')</script>";
  		echo "<a href=?hal=dekrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if(substr($uploaded_name,0,7)!="Enkrip_"){
  		echo "<script>alert('File yang dimasukan bukan hasil enkripsi')</script>";
  		echo "<a href=?hal=dekrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if(strlen($kcf)<8){
  		echo "<script>alert('Password Kurang dari 8 karakter atau Password Kosong!')</script>";
  		echo "<a href=?hal=dekrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}

  	move_uploaded_file($_FILES["file"]["tmp_name"],"hasil/temp");
  	$nama_file = str_replace("Enkrip", "Dekrip", $_FILES["file"]["name"]);

  	$fp = fopen("hasil/".$nama_file,"w");
  	fwrite($fp, $plain);
  	fclose($fp);
  	$time_end = microtime_float();
  	$time = $time_end - $time_start;
?>

	<!-- /. NAV SIDE  -->
	<div id="page-wrapper" >
		<div id="page-inner">
			<div class="row">
				<div class="col-lg-12">
					<h2><i class="fa fa-edit"></i> Hasil Decrypt</h2>
				</div>
			</div>

			<!-- /. ROW  -->
			<hr />
			<div class="row">
				<div class="alert alert-info col-lg-12">
					<table border="0" width="400px">
						<tr>
							<td width="150"><font color="black"><b>Nama File</b></font></td>
							<td width="50"><font color="black">:</font></td>
							<td width="200"><font color="black"><?php echo $_FILES["file"]["name"]; ?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Type File</font></b></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo $_FILES["file"]["type"]; ?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Ukuran File</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo ($_FILES["file"]["size"] / 1024); ?> Kb</font></td>
						</tr>
						<tr>
							<td><font color="black"><b>File Hasil</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo $nama_file; ?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Waktu Proses</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo "$time seconds\n"; ?></font></td>
						</tr>
					</table>
					<br />

					<?
						mysql_query("insert into file (nama_file,kunci,nip) values('$nama_file','$pw','$nip')")
					?>

					<div class="col-lg-6">
            <div class="col-lg-2">
              <a href="dekrip.php"><button class="btn btn-primary">Kembali</button></a>
            </div>
            <div class="col-lg-2">
              <a href="<?php echo 'download.php?download_file='.$nama_file ?>"><button class='btn btn-warning'>Download</button></a>
            </div>
            <div class="col-lg-2">
              <a href="enkrip.php"><button class="btn btn-success">Encrypt</button></a>
            </div>
          </div>

					<!-- <table style="width: 100px">
						<tr>
							<td><a href="dekrip.php"><button name ='Kembali' class="btn btn-primary">KEMBALI</button></a></td>
						</tr>
					</table> -->
					<?php
						}else{
							echo "<script>alert('File Gagal di Dekrip');window.location = 'dekrip.php';</script";
						}
					?>
				</div>
      </div>
		</div>
  </div>
</div>

<?php
	include 'footer.php';
?>
