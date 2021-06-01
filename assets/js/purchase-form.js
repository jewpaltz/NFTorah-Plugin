const contractAddress = "0x123bfCD4ff6B5A1aD7301Fe34256a3b5588bFD3C"; //NFTorah v1
const privateKey = '0x7905d58ec073b68899ca471f12bb80fdd750c692f5be08a90d6cc94c92288785';
const infura_projectId = '0cbb0f724be040dd85aaf1ed5fbf9fb6';
const provider = new ethers.providers.InfuraProvider("rinkeby", infura_projectId);
const minter_wallet = new ethers.Wallet(privateKey, provider);

class Letter {
    hebrewName = "";
    secularName = "";
    lastName = "";
    mothersName = "";
    hasOneFirstName(){
        return this.hebrewName || this.secularName;
    }
}

class Purchase {
    firstName = null;
    lastName = null;
    email = null;
    phone = null;
    cardNumber = null;
    expirationDate = null;
    cvv = null;
    paymentMethodId = null;
}

let wallet = null;

const formVue = new Vue({
    el: '#postbox',
    data: ()=>({
        letters: [new Letter()],
        activeItemTab: "1",
        activePaymentTab: "1",
        isSaved: false,
        isLoading: false,
        purchase: new Purchase(),
        route: window.location.hash,

        card_el: null,

        mnemonic: null,
        wallet_address: null,
        json_wallet: null,
        contract_address: contractAddress,
        token_id: null,
        new_address: null,
        transfer_tx: null,
    }),
    mounted(){
        this.card_el = setupStripeElements();
        this.$watch('letters.0.hebrewName', (newVal, oldVal)=>{
            if(!this.purchase.firstName || this.purchase.firstName == oldVal){
                this.purchase.firstName = newVal;
            }
        })
        this.$watch('letters.0.secularName', (newVal, oldVal)=>{
            if(!this.purchase.firstName || this.purchase.firstName == oldVal || this.purchase.firstName == this.letters[0].hebrewName){
                this.purchase.firstName = newVal;
            }
        })
        this.$watch('letters.0.lastName', (newVal, oldVal)=>{
            if(!this.purchase.lastName || this.purchase.lastName == oldVal){
                this.purchase.lastName = newVal;
            }
        })

        this.setupPayPalButtons();
    },
    methods: {
        addLetter(){
            this.letters.push(new Letter());
        },
        deleteLetter(i){
            this.letters.splice(i,1);
        },

        creditcard(){
            this.isLoading = true;
            if (this.isValid()) {
                try {
                    const payment_results = await pay_stripe(stripe, this.card_el);
                    console.log({payment_results});
                    
                    this.purchase.cardNumber = payment_results.paymentMethod.card.last4;
                    this.purchase.expirationDate = payment_results.paymentMethod.card.exp_month + "/" + payment_results.paymentMethod.card.exp_year;
                    this.purchase.paymentMethodId = payment_results.paymentMethod.id;           
                } catch (error) {
                    console.error(error);
                    toastError(error.message ?? error);
                    this.isLoading = false;
                    return;
                }
                this.submitForm();
            }
        },

        isValid(){
            if(!this.$refs.form.checkValidity()){
                for (const field of this.$refs.form) {
                    if(field.blur){
                        field.dispatchEvent(new Event('blur'));
                    }
                }
                this.$refs.form.reportValidity();

                return false;
            }
            return true;
        },
        async submitForm(){

            this.purchase.paid = this.price;
            const data = await  NFTorah_api('purchases', {
                purchase: this.purchase,
                letters: this.letters
            });
            console.log({data});

            this.letters = data.purchase.letters;
            this.purchase.id = data.purchase.purchase_id;
            this.isLoading = false;

            history.pushState(null, null, "#download-nft")
            this.route = "#download-nft";
            this.isSaved = true;

            this.mintNFT();
        },

        setupPayPalButtons(){
            /*global paypal*/ //    injected by paypals hosted script
            const vm = this;
            paypal.Buttons({
                createOrder: (data, actions) => {
                    if (!vm.isValid()) {
                        return false;
                    }
        
                  return actions.order.create({
                    purchase_units: [{
                      amount: {
                        value: vm.price
                      }
                    }]
                  });
                },
                onApprove: function(data, actions) {
                  return actions.order.capture().then(function(details) {
                    return vm.submitForm();
                  });
                }
              }).render('#paypal-button-container'); // Display payment options on your web page
        },

        async saveCryptoInfo(token_id, wallet_address, mnemonic, wallet_private_key){
            const response = await NFTorah_api('purchases/crypto', {
                token_id, wallet_address, mnemonic, wallet_private_key
            });
        },
        async mintNFT(){
            /*
                1. Create burner wallet
                2. Mint NFT to burner wallet
                    a. In the DEBUG version we will gaaaasp... send the private key of the contract owner to the JS in the client
                    b. For a running on mainnet we will need a solution that keeps the private key secure. probably by doing the signing in php.
                3. Send the wallet keys & token_id to the server
                4. Display the info
            */
                                
            const abi = [
                //"function mint(address to) public returns (uint256)",
                "function mint(address to) public",
                "event Transfer(address indexed from, address indexed to, uint256 indexed value)",
            ];

            wallet = ethers.Wallet.createRandom();
            this.mnemonic = wallet.mnemonic;
            this.wallet_address = wallet.address;

            const contract = new ethers.Contract(contractAddress, abi, provider);
            const contractWithSigner = contract.connect(minter_wallet);

            const filter = contractWithSigner.filters.Transfer(null, wallet.address);
            contractWithSigner.on(filter, (from, to, value, event) => {
                console.log({ event });
                this.token_id = value;
            });

            const tx = await contractWithSigner.mint(wallet.address);
            // The operation is NOT complete yet; we must wait until it is mined
            // const mined_tx = await tx.wait();
        },
        async transfer(){
            const abi = [
                "function safeTransferFrom(address from, address to, uint256 tokenId) public"
            ]

            wallet = new ethers.Wallet( wallet.privateKey, provider);

            const contract = new ethers.Contract(contractAddress, abi, provider); //TODO create abi
            const contractWithSigner = contract.connect(wallet);

            /*
            const gasNeeded = contractWithSigner.estimateGas.safeTransferFrom()
            const tx = minter_wallet.sendTransaction({
                to: wallet,
                value: ethers.utils.parseEther("1.0")
            });
            */

            this.transfer_tx = await contractWithSigner.safeTransferFrom(wallet.address, this.new_address, this.token_id);
            this.transfer_tx = await this.transfer_tx.wait();
        },
        async encryptWallet(){
            this.json_wallet = 'processing';
            const password = prompt("Choose a password");
            const json_wallet = await wallet.encrypt( password );
            this.json_wallet = 'data:text/plain;charset=utf-8,' + encodeURIComponent(json_wallet);
        }
    },
    computed:{
        price(){ // paid
            return this.letters.length;
        },
        page(){
            return this.route.includes("download-nft")  ? //|| this.isSaved
                2 :
                1 ;
        },
        a_letter(){
            return this.letters.length == 1 ? "a letter" : this.letters.length + " letters"
        }
    },
});       


window.addEventListener('hashchange', function() {
    formVue.route = window.location.hash;
  }, false);