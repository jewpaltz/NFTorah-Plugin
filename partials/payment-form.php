<b-tabs type="is-toggle" v-model="activePaymentTab">
        <b-tab-item label="Credit Card"  value="1">
            <div class="sr-input sr-card-element box" id="card-element"></div>
            <div class="sr-field-error" id="card-errors" role="alert"></div>
            <b-button  type="is-success " size="is-large" expanded  @click.prevent="creditcard">
                Mint My Torah Tokens
            </b-button>

        </b-tab-item>

        <b-tab-item  value="2">
            <template slot="header">
                PayPal
                <img class="icon" style="height: 20px; width: 20px;" src="<?= plugins_url( 'NFTorah-plugin/assets/images/coming_soon.png' ) ?>" />
            </template>
            <p>
                Once you complete the transaction in PayPal. You will be returned to this page to download your NFTs
            </p>
            <b-button  type="is-success " size="is-large" expanded @click.prevent="paypal">
                Continue to Paypal
            </b-button>
        </b-tab-item>

        <b-tab-item value="3">
            <template slot="header">
                CryptoCurrency 
                <img class="icon" style="height: 20px; width: 20px;" src="<?= plugins_url( 'NFTorah-plugin/assets/images/coming_soon.png' ) ?>" />
            </template>
        <ul>
            <li>
                <b>Option 1:</b> Use an integrated Web3 wallet like MetaMask.
                <button class="button">Open Wallet</button>
            </li>
            <li>
                <b>Option 2:</b> Include your one time code in your transaction. Your code is valid for 7 days. Your NFT will be minted in the transaction of your payment.
                <button class="button">Get my Code</button>
            </li> 
        </ul>
        </b-tab-item>
</b-tabs>
