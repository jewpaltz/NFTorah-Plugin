<?php
/*
    B"H
    Expects a variable named $data with the list to be displayed
*/?>
<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    <tr>
        <th>Name</th>
        <th>Email / Phone</th>
         <th>Payment Amount</th>
        <th>CC Info</th>
        <th>Purchase Date</th>
        <th>
            Letters
        </th>
    </tr>
    <?php foreach ($data as $key => $rs): ?>
        <tr>
            <td>
                <?=$rs['firstName']?> <?=$rs['lastName']?>
            </td>
            <td>
                <?=$rs['email']?> <br />
                <?=$rs['phone']?>
            </td>
            <td><?=$rs['paid']?></td>
            <td>
                ##-##-<?=$rs['cardNumber']?><br />
                <?=$rs['expirationDate']?> <?=$rs['cvv']?> 
            </td>
            <td><?=$rs['created_at']?></td>
            <td>
                <table class="table">
                    <!-- <tr><th>Hebrew Name</th><th>Secular Name</th><th>Last Name</th><th>Mother's Name</th></tr> -->
                    <?php foreach ($rs['letters'] as $l_key => $letter): ?>
                        <tr>
                            <td><?=$letter['hebrewName']?></td>
                            <td><?=$letter['secularName']?></td>
                            <td><?=$letter['lastName']?></td>
                            <td><?=$letter['mothersName']?></td>
                        </tr>
                    <?php endforeach; ?>                        
                </table>
            
            </td>
        </tr>
    <?php endforeach; ?>
</table>