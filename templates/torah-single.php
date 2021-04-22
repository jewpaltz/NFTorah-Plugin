<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/torah/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

 $torah = [
     "id"=>1
 ]
?>
<div class="torah-campaign" id="torah-campaign-<?php=$torah.id?>">
    <h1> Torah Campaign</h1>
</div>


<?php
    require __DIR__ . '/partials/purchase-form.php';
?>