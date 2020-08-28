<?= $this->session->flashdata('pesan'); ?>
<?php
if ($this->session->userdata('id_role') == '2') {
    echo "Member";
} else {
    echo "Administrator";
}
?>
<h1>sukses</h1>