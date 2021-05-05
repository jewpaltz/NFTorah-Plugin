    <?php include __DIR__ . '/progress.php'; ?>
<div id="postbox">
    <form id="purchase-form" ref="form" method="post" @submit.prevent="" v-if="page == 1">

        <b-tabs v-model="activeItemTab">
            <b-tab-item label="Individual Letters" value="1">
                <?php include __DIR__ . '/letters-form.php'; ?>
            </b-tab-item>
            <b-tab-item label="Verses & Sections" value="2">
                <h1>Buy an entire Verse or Parshah</h1>
            </b-tab-item>
            <b-tab-item label="Special Sections" value="3">
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
                <b-field label="First Name *" label-position="inside">
                        <b-input v-model="purchase.firstName" id="firstName" required></b-input>
                    </b-field>
                </div>
                <div class="column">
                <b-field label="Last Name *" label-position="inside">
                    <b-input v-model="purchase.lastName" id="lastName" required></b-input>
                </b-field>
            </div>
        </div>
        <b-field label="Email *" label-position="inside">
            <b-input v-model="purchase.email" id="email" required></b-input>
        </b-field>
        <b-field label="Phone *" label-position="inside">
            <b-input v-model="purchase.phone" id="phone" required></b-input>
        </b-field>

        <?php include __DIR__ . '/payment-form.php'; ?>


        <?php wp_nonce_field( 'wps-frontend-post' ); ?>

    
    </form>
    <div v-else-if="page == 2">
        <?php include __DIR__ . '/download-nft.php'; ?>
    </div>
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

    .field .control, .field .help {
        margin: 0;
    }

    .b-tabs section.tab-content {
        padding: 0;
    }

    p.panel-heading {
        font-size: 1.1em;
        padding: .5em 1em;
    }

    .title {
        margin-top: .5rem !important;
        margin-bottom: 0 !important; 
    }
    .subtitle {
        margin-top: 0 !important;
        margin-bottom: .5rem !important;
    }

    @media (max-width: 767px){
        .entry-content-wrap {
            padding: .5rem;
        }       
    }

</style>
<?php


?>