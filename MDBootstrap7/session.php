<?php
require('header.php');
// hieronder zet je PHP code
require('startHTML.php');
?>
<!-- hier linken extra styling en css -->
<style>

</style>
<?php
require('navbar.php');
?>
<div class="container-fluid mt-5" id="top">
<div class="d-flex justify-content-center align-items-center">
    <div class="card mt-5">
        <div class="card-header bg-donkerrood text-white text-center font-weight-bold">
        <h3><i class="fas fa-clipboard-list"></i>&nbsp;inhoud van $_SESSION</h3>
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
			<pre>
			<?php
				print_r($_SESSION);
			?>
			</pre>
            </blockquote>
        </div>
    </div>
</div>
</div>
<br>
<?php
require('footer1.php');
?>
<!-- Custom scripts -->
<script type="text/javascript">

</script>
<?php
require('footer2.php');
?>