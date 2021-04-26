<b-tabs type="is-toggle" size="is-large">
        <b-tab-item label="Credit Card">
            <b-field label="Card Number" label-position="inside">
                <b-input v-model="cardNumber" id="cardNumber"></b-input>
            </b-field>
            <div class="columns">
                <div class="column">
                    <b-field label="Expiration Date" label-position="inside">
                        <b-input v-model="exp" id="exp"></b-input>
                    </b-field>
                </div>
                <div class="column">
                    <b-field label="CVV" label-position="inside">
                        <b-input v-model="cvv" id="cvv"></b-input>
                    </b-field>
                </div>
            </div>
            <b-button  type="is-success " size="is-large" expanded >
                Mint My Torah Tokens
            </b-button>
                
        </b-tab-item>

        <b-tab-item label="PayPal">
            <p>
                Once you complete the transaction in PayPal. You will be returned to this page to download your NFTs
            </p>
            <b-button  type="is-success " size="is-large" expanded >
                Continue to Paypal
            </b-button>
        </b-tab-item>

        <b-tab-item label="CryptoCurrency">
            <h3 class="title is-3">Send payment to any of the following addresses</h3>
            <h4 class="sub-title">NFT will be minted after 4 confirmations</h4>
        </b-tab-item>
</b-tabs>
