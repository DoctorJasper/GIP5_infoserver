<script type="text/javascript">
    let basicInstance;
    <?php
    if (isset($_SESSION['toastr'])) {
        for ($t = 0; $t < count($_SESSION['toastr']); $t++) {
        ?>
            basicInstance = mdb.Toast.getInstance(document.getElementById("toast<?php echo $t; ?>"));
            basicInstance.show();
        <?php 
        } 
    }
    unset($_SESSION['toastr']);
    ?>
</script>
</html>