<?php
  	include 'header.php';

  	//Memanggil class aes
	include 'class/aes.class.php';
	include 'class/aesctr.class.php';

	//Memanggil class huffman
	// include 'class/huffmancoding.php';

  //Fungsi Proses Enkripsi RC4
  function setupkey(){  //proses KSA key scheduling algoritm
    error_reporting(E_ALL ^ (E_NOTICE));

    $pass = $_POST["katakunci"];
    $key=array();
    for($i=0;$i<256;$i++){
      $key[$i]=ord($pass[$i % strlen($pass)]);
    }//ambil nilai ASCII dari tiap karakter password
     //masukan password ke array key secara berulang sampai penuh

     //isi array s
    global $s;
    $s=array();
    for($i=0;$i<256;$i++){
      $s[$i] = $i;//isi array s 0 s/d 255
    }

     //permutasi/pengacakan isi array s
    $j = 0;
    $i = 0;
    for($i=0;$i<256;$i++){
      $a = $s[$i];
      $j = ($j + $s[$i] + $key[$i]) % 256;
      $s[$i] = $s[$j]; //swap
      $s[$j] = $a;
    }
  }

  //proses PRGA
  function enkrip($plainttext){
    global $s;
    $x=0;$y=0;
    $ciper='';
    for($n=1;$n<= strlen($plainttext);$n++){
      $x = ($x+1) % 256;
      $a = $s[$x];
      $y = ($y+$a) % 256;
      $s[$x] = $b = $s[$y];//swap
      $s[$y] = $a;
      /*proses XOR antara plaintext dengan kunci
      dengan $plainttext sebagai plaintext
      dan $s sebagai kunci*/
      $ciper = ($plainttext^$s[($a+$b) % 256]) % 256;
      return $ciper;
    }
  }

	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', '-1');

	$timer = microtime(true);

  $panjangpass = $_POST['katakunci']; //key untuk rc4
	$pw = $_POST['kunci']; //key untuk aes
	$pt = $_FILES['file']['name'];
  $uploaded_ext = substr($pt, strrpos($pt, '.') + 1);
	$uploaded_size = $_FILES["file"]["size"];
	$cipher = empty($_POST['cipher']) ? '' : $_POST['cipher'];

	$encr = empty($_POST['encr']) ? $cipher : AesCtr::encrypt($pt, $pw, 256);

	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	$time_start = microtime_float();

	if ($_FILES['file']['error'] == UPLOAD_ERR_OK
		&& is_uploaded_file($_FILES['file']['tmp_name'])){
			$pt = file_get_contents($_FILES['file']['tmp_name']);
			$cipher = AesCtr::encrypt($pt, $pw, 256);

		//Memulai proses kompresi
		// $komp = $cipher;

		// $encoding = HuffmanCoding::createCodeTree($komp);
		// $encoded = HuffmanCoding::encode ($komp, $encoding);

    // move_uploaded_file($_FILES["file"]["tmp_name"],"hasil/temp");
    // $isifile = file_get_contents("hasil/temp");

    // Algoritma Enkripsi RC4
    setupkey();
    for($i=0;$i<strlen($cipher);$i++){
     $kode[$i]=ord($cipher[$i]); /*rubah ASCII ke desimal*/
     $b[$i]=enkrip($kode[$i]); /*proses enkripsi RC4*/
     $c[$i]=chr($b[$i]);
    }
    $hasil = '';
    for($i=0;$i<strlen($cipher);$i++){
      $hasil = $hasil . $c[$i];
    }


		if(strlen($pw)<8){
			echo "<script>alert('Password Kurang dari 8 Karakter');window.location='enkrip.php';</script>";
     			return;
		}
 		if($uploaded_ext != "txt" && $uploaded_ext != "xls" && $uploaded_ext != "xlsx" && $uploaded_ext != "pdf" && $uploaded_ext != "docx" && $uploaded_ext != "doc"){
				echo "<script>alert('File Harus .doc, .docx, .xls, .xlsx, .pdf, atau .txt');window.location='enkrip.php';</script>";
				return;
		}

    if($_FILES["file"]["error"] != 0){
  		echo "<script>alert('Tidak ada file yang diupload!')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if(strlen($panjangpass)<8){
  		echo "<script>alert('Password kurang dari 8 atau Password Kosong!')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if($uploaded_ext != "txt" && $uploaded_ext != "xls" && $uploaded_ext != "xlsx" && $uploaded_ext != "pdf" && $uploaded_ext != "docx" && $uploaded_ext != "doc"){
  		echo "<script>alert('File yang dipilih tidak valid')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if($uploaded_size > 2097152){
  		echo "<script>alert('File yang dimasukan lebih besar dari 2MB')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}


  	//Menyimpan File Hasil
    move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
	  $namafile = $_FILES['file']['name'];
    $nm_file= preg_replace("/\s+/", "_", $namafile);
    // $nm_file= str_replace(" ","_", $rep);


		$fp = fopen("hasil/Enkrip_".$nm_file,"w");
		fwrite($fp, $hasil);
		fclose($fp);
		$time_end = microtime_float();
		$time = $time_end - $time_start;
    $nama_file = "Enkrip_".$nm_file;
?>

	<!-- /. NAV SIDE  -->
	<div id="page-wrapper" >
		<div id="page-inner">
			<div class="row">
				<div class="col-lg-12">
					<h2><i class="fa fa-edit"></i> Hasil Encrypt</h2>
				</div>
			</div>

			<!-- /. ROW  -->
			<hr />
			<div class="row">
				<div class="alert alert-info col-lg-12">
					<table border="0" width="600px">
						<tr>
							<td width="150"><font color="black"><b>Nama File</b></font></td>
							<td width="30"><font color="black">:</font></td>
							<td width="300"><font color="black"><?php echo $_FILES["file"]["name"];?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Type File</font></b></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo $_FILES["file"]["type"];?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Ukuran File</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo ($_FILES["file"]["size"] / 1024);?> Kb</font></td>
						</tr>
           				<tr>
							<td><font color="black"><b>File Hasil</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo $nama_file;?></font></td>
						</tr>
						<tr>
							<td><font color="black"><b>Waktu Proses</b></font></td>
							<td><font color="black">:</font></td>
							<td><font color="black"><?php echo "$time seconds\n";?></font></td>
						</tr>
					</table>
					<br />

					<?php
						// mysql_query("insert into file (nama_file,kunci,nip) values('$nama_file','$pw','$nip')");
					?>
          <div class="col-lg-6">
            <div class="col-lg-2">
              <a href="enkrip.php"><button class="btn btn-primary">Kembali</button></a>
            </div>
            <div class="col-lg-2">
              <a href="<?php echo 'download.php?download_file='.$nama_file ?>"><button class='btn btn-warning'>Download</button></a>
            </div>
            <div class="col-lg-2">
              <a href="dekrip.php"><button class="btn btn-success">Decrypt</button></a>
            </div>
          </div>
					<!-- <table style="width: 100px">
						<tr>
							<td><a href="enkrip.php"><button name ='Kembali' class="btn btn-primary">KEMBALI</button></a></td>
              <td><a href="dekrip.php"><button class="btn btn-primary">Decrypt</button></a></td>
						</tr>
					</table> -->

					<?php
						}else{
							echo "<script>alert('File Gagal di Enkrip');window.location = 'enkrip.php';</script>";
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
