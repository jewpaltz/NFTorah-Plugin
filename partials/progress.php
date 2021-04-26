<?php
    /*  B"H
    Place Holders
    */
    $letters_available = 304805;
    $letters_purchased = 18613;
    $letters_remaining = $letters_available - $letters_purchased;
    $percent_done = (int) (($letters_purchased / $letters_available) * 100);
?>

<b-progress type="is-danger" :value="40" show-value>
</b-progress>

<div class="flat-progress">
    <div class="done" style="width: <?= $percent_done ?>%;"></div>
    <div class="text">
        <span class="digits" ><?= $letters_purchased; ?> </span>
        letters purchased
    </div>
</div>
<style>
.flat-progress {
    background: white;
    border: 1px solid #AABBFF;
    height: 66px;
    border-radius: 4px;
    position: relative;
    display: flex;
    justify-content: center;

    overflow: hidden;
}
.flat-progress .done {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #D80027;
    transition: all 3.3s linear;
}
.flat-progress .text {
    position: relative;
    color: #040404;
    height: 66px;
    display: flex;
    align-items: center;
    padding: 0 40px;
    font-weight: 700;
    font-size: 19px;
}
.flat-progress .digits {
    margin-right: 5px;
}
</style>