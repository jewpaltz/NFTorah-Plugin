
<h4 class="title is-4">Congratulations! You own {{a_letter}} in a real Torah.</h4>
<h6 class="subtitle is-6">Plus an NFT to prove that it's yours</h6>


<div class="message is-success ">
    <div class="message-body">
        <h5 class="title is-5">View your token on OpenSea</h5>
        <div>
            You can view your Torah Token on the worlds largest NFT marketplace.
            <h6 v-show="!token_id">
                As soon as your NFT is finished being minted a link will show up right here.
                <b-skeleton ></b-skeleton>
            </h6>
            <div v-show="token_id">
                <b>Just make sure to return to this page to claim your ownership.</b>
                <a :href="`https://testnets.opensea.io/assets/${contract_address}/${token_id}`" target="_blank">View My Token</a>
            </div>
        </div>
    </div>
</div>

<img :src="`https://zaidyla.com/wp-content/uploads/2021/05/NFTorah-Certificate-Placeholder.png`" width="50%" /> <!-- will be dynamic for each letter eventually -->

<h4 class="subtitle is-4">
    Blockchain is powerful. But it doesn't need to be difficult.
    <b>You have a few options:</b>
</h4>
<article class="panel is-success">
  <p class="panel-heading">
    Option 1: Download your wallet
  </p>
  <div class="panel-block">
    <div>
        While NFTs live on the blockchain. Their ownership is determined by the person that is able to wield the wallet that "contains" them.<br />
        We've created a temporary wallet for you and placed ownership of the NFT in it.<br />
        You can download it now and import it into any Ethereum wallet to take full control. But first you must add a password to it. <br />
        <i class="has-text-danger">This wallet isn't very secure. Please don't store any money or cryptocurrency in it.</i> <br />
        <button class="button" @click.prevent="encryptWallet" v-if="!json_wallet">Add a Password</button>
        <div v-if="json_wallet=='processing'">
            Please wait while we encrypt your Wallet
            <b-skeleton ></b-skeleton>
        </div>
        <a :href="json_wallet" download="NFTorah_Wallet.json" v-if="json_wallet && json_wallet!='processing'">Download Wallet</a>
    </div>
  </div>
</article>
<article class="panel is-success">
  <p class="panel-heading">
    Option 2: Write down your seed phrase
  </p>
  <div class="panel-block">
    <p>
        Write down this secret phrase on a piece of paper. It is the secret key by which any Ethereum wallet should be able to access your token.
        <i class="has-text-danger">We will also be keeping a key on our server. Which isn't very secure practice. So, please don't store any money or cryptocurrency in it.</i> <br />
        <b-skeleton v-if="!mnemonic"></b-skeleton>
        <code style="display: block; font-size: x-large; padding:15px; " v-show="mnemonic">{{mnemonic}}</code>
    </p>
  </div>
</article>
<article class="panel is-success">
  <p class="panel-heading">
    Option 3: Transfer Ownership To Secure Wallet
  </p>
  <div class="panel-block">
    <form>
        Enter the public address of any Ethereum wallet (created with metamask, myetherwallet, coinomi, or any other wallet), and we'll transfer ownership immediately 
        <i class="has-text-success has-text-weight-bold">This is the most secure option.</i> <br />
        <b-field message="Please make sure that the address is on the correct network" v-show="!transfer_tx">
            <b-input type="text" icon="magnify" placeholder="Ethereum Address" expanded v-model="new_address"></b-input>
            <p class="control">
                <b-button type="is-success" label="Transfer" @click.prevent="transfer" />
            </p>
        </b-field>
        <div  v-if="transfer_tx && !transfer_tx.blockNumber">
            The transfer has been sent. However it may take a while to be mined. You can wait here, or you can track it on Etherscan at
            <a :href="`https://rinkeby.etherscan.io/tx/${transfer_tx.transactionHash}`">Temporary Transaction</a>
            <b-skeleton ></b-skeleton>
        </div>
        <div class="message is-success" v-if="transfer_tx && transfer_tx.blockNumber">
            <div class="message-body">Mazal Tov! Your NFTorah has been transferred to your secure wallet.</div>
        </div>
    </form>
  </div>
</article>
<article class="panel is-success">
  <p class="panel-heading">
    Option 4: Come back at any point in the future
  </p>
  <div class="panel-block">
    <p>
        Come back in the future, and we will send you a secret link to the email address that you entered while paying for the letters.<br />
        That link will let you do any one of the other options whenever you are ready. 
    </p>
  </div>
</article>
