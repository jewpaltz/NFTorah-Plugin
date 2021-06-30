    <?php include __DIR__ . '/progress.php'; ?>
<div id="postbox" :class="{ loaded: true }">
    <form id="purchase-form" ref="form" method="post" @submit.prevent="" v-if="page == 1">
        <h3 class="title is-3"> Purchase a letter </h3>
        <b-tabs v-model="activeItemTab">
            <b-tab-item label="Individual Letters" value="1">
                <?php include __DIR__ . '/letters-form.php'; ?>
            </b-tab-item>
            <b-tab-item value="2">
                <template slot="header">
                    Verses & Sections
                    <img class="icon" style="height: 20px; width: 20px;" src="<?= plugins_url( 'NFTorah-plugin/assets/images/coming_soon.png' ) ?>" />
                </template>
                <?php include __DIR__ . '/verses.php'; ?>
            </b-tab-item>
            <?php /*
            <b-tab-item value="3">
                <template slot="header">
                    Special Sections
                    <img class="icon" style="height: 20px; width: 20px;" src="<?= plugins_url( 'NFTorah-plugin/assets/images/coming_soon.png' ) ?>" />
                </template>
                <?php include __DIR__ . '/special-sections-form.php'; ?>
            </b-tab-item>
            */ ?>
        </b-tabs>

        <div class="level">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Price</p>
                    <p class="title">${{price}}</p>
                </div>
            </div>
        </div>

        <div class="box">
            <small><b>Information of person paying:</b></small>
            <div class="columns" style="margin-bottom: .75rem;">
                <div class="column" style="padding-bottom: 0;">
                    <b-field label="First Name *" label-position="inside">
                            <b-input v-model="purchase.firstName" id="firstName" required></b-input>
                        </b-field>
                    </div>
                    <div class="column" style="padding-bottom: 0;">
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
        </div>

        <?php include __DIR__ . '/payment-form.php'; ?>


        <?php wp_nonce_field( 'wps-frontend-post' ); ?>

    
    </form>
    <div v-else-if="page == 2">
        <?php include __DIR__ . '/download-nft.php'; ?>
    </div>
    <div class="noscript" id="noscript">
        <article class="message is-danger" style="margin-top: 5px;" id="app-noscript">
            <div class="message-body">
                <h1 class="title is-4 has-text-danger-dark">Sorry, this website doesn't support your web browser.</h1>
                <h2 class="subtitle is-4 has-text-danger-dark">Please try opening this site in chrome or on a desktop computer.</h2>
            </div>
        </article>
        <div id="app-loading">
        <h1 class="title is-4 has-text-success-dark">Loading...</h1>
            <b-skeleton height="80px"></b-skeleton>
            <b-skeleton height="80px"></b-skeleton>
            <b-skeleton height="80px"></b-skeleton>
        </div>
    </div>

    <div class="notification is-warning is-light">
        <img class="icon" style="height: 20px; width: 20px;" src="<?= plugins_url( 'NFTorah-plugin/assets/images/coming_soon.png' ) ?>" />
        Feature not yet available. Coming Soon!
    </div>
</div>

<style>
    #postbox > *, #postbox > *,
    .loaded#postbox .noscript,
    #postbox .noscript #app-loading,
    #postbox .noscript.app-loading #app-noscript {
        display: none;
    }
    .loaded#postbox > *,
    #postbox .noscript,
    #postbox .noscript.app-loading #app-loading  {
        display: block;
    }
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

    .table-form td{
        white-space: nowrap;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 767px){
        .entry-content-wrap {
            padding: .5rem;
        }       
    }

</style>
<script type="application/javascript">
    const noscriptBox = document.getElementById("noscript");
    const loadingTitle = document.querySelector("#app-loading .title");
    noscriptBox.classList.add('app-loading');
    let loadingI = 0; 
    const loadingInterval = setInterval(()=>{
        loadingI++;
        loadingTitle.innerHTML = "Loading" + ".".repeat(loadingI);
        if(loadingI > 16){
            clearInterval(loadingInterval);
            noscriptBox.classList.remove('app-loading');
        }
    }, 500)
</script>
<?php


?>