<b-tabs type="is-toggle" v-model="purchase.paymentMethod">
        <b-tab-item label="Credit Card"  value="CREDIT_CARD">
            <div class="sr-input sr-card-element box" id="card-element"></div>
            <div class="sr-field-error" id="card-errors" role="alert"></div>
            <b-button  type="is-success" size="is-medium" expanded :loading="isLoading"  @click.prevent="creditcard">
                Mint My Torah Tokens
            </b-button>

        </b-tab-item>

        <b-tab-item  value="PAYPAL">
            <template slot="header"> PayPal </template>
            <div class="box">
                <div id="paypal-button-container"></div>
            </div>
        </b-tab-item>

        <b-tab-item value="ETHER">
            <template slot="header"> CryptoCurrency  </template>
            <div class="box">
                <p class="block">
                    Send at least {{one_dollar_in_eth}} ETH to {{contract_address}} using an integrated Web3 wallet like MetaMask.
                </p>
                <b-button  type="is-success" size="is-medium" expanded :loading="isLoading"  @click.prevent="cryptoPayment">
                    Mint My Torah Tokens
                </b-button>
                
            </div>
        </b-tab-item>
</b-tabs>
