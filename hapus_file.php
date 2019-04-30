<?php
	// include '../koneksi.php';

	$cek = mysql_query("SELECT * FROM file WHERE kd_file="$_GET['id']);
	$row = mysql_fetch_array($cek);
	mysql_query("DELETE FROM file WHERE kd_file="$_GET['id']);
	unlink("hasil/".$row['nama_file']);

	echo "<script type='text/javascript'>alert('File Berhasil di Hapus!');</script>";
	echo "<meta http-equiv='refresh' content='0; url=list.php'>";
?>
