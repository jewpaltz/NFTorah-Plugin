<div id="postbox">

    <?php include __DIR__ . '/progress.php'; ?>

    <form id="purchase-form" name="purchase-form" method="post">

        <b-tabs v-model="activeTab">
            <b-tab-item label="Individual Letters">
                <?php include __DIR__ . '/letters-form.php'; ?>
            </b-tab-item>
            <b-tab-item label="Verses & Sections">
                <h1>Buy an entire Verse or Parshah</h1>
            </b-tab-item>
            <b-tab-item label="Special Sections">
                <?php include __DIR__ . '/special-sections-form.php'; ?>
            </b-tab-item>
        </b-tabs>

        <div class="has-text-centered">
                <p class="heading">Price</p>
                <div style="font-size:36pt; font-weight: 900">${{price}}</div>

        </div>

        <small><b>Information of person paying:</b></small>
        <div class="columns">
            <div class="column">
                <b-field label="First Name" label-position="inside">
                        <b-input v-model="firstName" id="firstName"></b-input>
                    </b-field>
                </div>
                <div class="column">
                <b-field label="Last Name" label-position="inside">
                    <b-input v-model="billingName" id="billingName"></b-input>
                </b-field>
            </div>
        </div>
        <b-field label="Email" label-position="inside">
            <b-input v-model="billingName" id="billingName"></b-input>
        </b-field>
        <b-field label="Phone" label-position="inside">
            <b-input v-model="billingName" id="billingName"></b-input>
        </b-field>

        <?php include __DIR__ . '/payment-form.php'; ?>


        <?php wp_nonce_field( 'wps-frontend-post' ); ?>

    
    </form>
</div>
<style>
    .columns:not(:last-child){
        margin-bottom: 0;
    }
    .box {
        position: relative;
    }
    .box>.delete {
        right: .5rem;
        position: absolute;
        top: .5rem;
    }

    .b-tabs section.tab-content {
        padding: 0;
    }
    @media (max-width: 767px){
        .entry-content-wrap {
            padding: .5rem;
        }       
    }

</style>
<?php


?>