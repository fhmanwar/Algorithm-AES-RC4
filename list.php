<?php
  	// include '../koneksi.php';
  	// include '../session.php';
  	include 'header.php';
?>

	<!-- /. NAV SIDE  -->
	<div id="page-wrapper" >
		<div id="page-inner">
			<div class="row">
				<div class="col-lg-12">
					<h2><i class="fa fa-file-o"></i> List File</h2>
				</div>
			</div>

			<!-- /. ROW  -->
			<hr />
			<div class="row">
				<div class="col-lg-12 ">
						<div class="panel-body">
						<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="300px"><center>NAMA FILE</center></th>
								<th width="200px"><center>PASSWORD</center></th>
								<th width="150px"><center>TANGGAL</center></th>
								<th width="150px"><center>USER</center></th>
							</tr>
						</thead>
						<tbody>
							<tr>
		    					<?php
			 						$no = 1;
			 						$get = mysql_query("SELECT * FROM file");
			 						while ($tampil=mysql_fetch_array($get)) {
								?>
								<td align="center"><?php echo $tampil['nama_file']; ?></td>
								<td align="center"><?php echo $tampil['kunci']; ?></td>
								<td align="center"><?php echo $tampil['tgl']; ?></td>
								<td align="center"><?php echo $tampil['nip']; ?></td>
	    					</tr>
	    				</tbody>

							<?php
		    					}
		 					?>

						</table>
						</div>
						</div>
				</div>
            </div>
		</div>
    </div>
</div>

<?php
	include 'footer.php';
?>
