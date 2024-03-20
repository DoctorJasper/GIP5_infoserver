<?php
if (isset($_SESSION['toastr'])) {
    for ($t = 0; $t < count($_SESSION['toastr']); $t++) {
        echo '<!-- Toasts -->
        <div class="toast fade mx-auto" id="toast'.$t.'" role="alert" aria-live="assertive" aria-atomic="true" data-mdb-toast-init
            data-mdb-autohide="true" data-mdb-delay="5000" data-mdb-position="top-right" data-mdb-append-to-body="true"
            data-mdb-stacking="true" data-mdb-width="350px" data-mdb-color="'.$_SESSION['toastr'][$t]['type'].'">
            <div class="toast-header">
                <i class="fas '.$_SESSION['toastr'][$t]['icon'].' fa-lg me-2"></i>
                <strong class="me-auto">'.$_SESSION['toastr'][$t]['title'].'</strong>
                <small>'.$_SESSION['toastr'][$t]['small'].'</small>
                <button type="button" class="btn-close" data-mdb-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">'.$_SESSION['toastr'][$t]['text'].'</div>
        </div>';
    }
}
?>
</body>

<!-- MDB ESSENTIAL -->
<script type="text/javascript" src="<?php echo $path; ?>js/mdb.umd.min.js"></script>
<!-- MDB PLUGINS -->
<script type="text/javascript" src="<?php echo $path; ?>plugins/js/all.min.js"></script>
<!-- Custom scripts -->
