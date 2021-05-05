<?php
/*
    B"H
    Expects a variable named $data with the list to be displayed
*/?>
<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Payment Amount</th>
        <th>CC Info</th>
        <th>Purchase Date</th>
    </tr>
    <?php foreach ($data as $key => $rs): ?>
        <tr>
            <td>
                <?=$rs['firstName']?> <?=$rs['lastName']?>
            </td>
            <td><?=$rs['email']?></td>
            <td><?=$rs['phone']?></td>
            <td><?=$rs['paid']?></td>
            <td>
                ##-##-<?=$rs['cardNumber']?><br />
                <?=$rs['expirationDate']?> <?=$rs['cvv']?> 
            </td>
            <td><?=$rs['created_at']?></td>
        </tr>
    <?php endforeach; ?>
</table>