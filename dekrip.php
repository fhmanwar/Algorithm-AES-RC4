<?php
  	include 'header.php';
?>

	<!-- /. NAV SIDE  -->
	<div id="page-wrapper">
		<div id="page-inner">
			<div class="row">
				<div class="col-lg-12">
					<h2><i class="fa fa-edit"></i> Decrypt</h2>
				</div>
			</div>

			<!-- /. ROW  -->
			<hr />
			<div class="row">
				<div class="alert alert-info col-lg-12">
					<form action="proses_dekrip.php" method="POST" enctype="multipart/form-data">
            <div class="col-md-12">
              <div class="col-md-2"><font color="Red">Masukkan File</font></div>
              <div class="col-md-10"><input type='file' name='file' class="form-group"/></div>
            </div>
            <br>
            <div class="col-md-12">
              <div class="col-md-2"><font color="Red">Masukkan Kunci</font></div>
              <div class="col-md-5"><input name="kunci" type="password" class="form-control" placeholder="Kunci Aes" maxlength="20"></div>
              <div class="col-md-5"><input name="katakunci" type="password" class="form-control" placeholder="Kunci Rc4" maxlength="20"></div>
            </div>
            <br>
            <div class="col-md-12">
              <input type="submit" class="btn btn-primary" value="SUBMIT">
            </div>
					</form>
				</div>
      </div>
		</div>
  </div>

<?php
	include 'footer.php';
?>
