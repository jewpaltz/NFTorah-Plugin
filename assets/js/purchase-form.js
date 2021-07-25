const contractAddress = wpApiSettings.CONTRACT_ADDRESS; //NFTorah
const abi = [
    //"function mint(address to) public returns (uint256)",
    "function mint(address to, uint256 tokenId) public",
    "event Transfer(address indexed from, address indexed to, uint256 indexed value)",
    "forceTransfer(address to, uint256 tokenId, bytes _data)",
];
const privateKey = '0x7905d58ec073b68899ca471f12bb80fdd750c692f5be08a90d6cc94c92288785';
const infura_projectId = '0cbb0f724be040dd85aaf1ed5fbf9fb6';
//  May be altered if user uses MetaMask to pay.
let provider = new ethers.providers.InfuraProvider("rinkeby", infura_projectId);
const PROPER_CHAIN_ID = 4;  // Rinkeby
const PROPER_CHAIN_NAME = 'rinkeby';
const minter_wallet = new ethers.Wallet(privateKey, provider);

window.ethereum.on('networkChanged', function(networkId){
    console.log('networkChanged',networkId);
  });

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
    paymentMethod = "CREDIT_CARD";
}

let wallet = null;

const formVue = new Vue({
    el: '#postbox',
    data: ()=>({
        letters: [new Letter()],
        verses: new Letter(),
        verses_count: 0,
        chapters_count: 0,
        activeItemTab: "1",
        activePaymentTab: "1",
        isSaved: false,
        isLoading: false,
        validationMessages: [],
        purchase: new Purchase(),
        route: window.location.hash,

        card_el: null,

        mnemonic: null,
        wallet_address: null,
        json_wallet: null,
        contract_address: contractAddress,
        token_ids: [],
        new_address: null,
        transfer_tx: null,
        one_dollar_in_eth: null,
    }),
    async mounted(){
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

        const response  = await fetch('https://api.coinbase.com/v2/exchange-rates?currency=USD')
        const data = await response.json();
        this.one_dollar_in_eth = roundCryptoValueString( data.data.rates.ETH );
    },
    methods: {
        addLetter(){
            this.letters.push(new Letter());
        },
        deleteLetter(i){
            this.letters.splice(i,1);
        },

        isValid(){
            this.validationMessages = [];
            if(!this.$refs.form.checkValidity()){
                for (const field of this.$refs.form) {
                    if(field.blur){
                        field.dispatchEvent(new Event('blur'));
                    }
                    if(field.validity.valueMissing){
                        field.setCustomValidity("is required")
                        console.log(field);
                        let section = field.closest("[section]")?.getAttribute("section");
                        this.validationMessages.push({ section, name: field.name, msg: field.validationMessage })
                    }
                }
                this.$refs.form.reportValidity();

                return false;
            }
            return true;
        },
        async submitForm(){

            const data = await  NFTorah_api('purchases', {
                purchase: this.purchase,
                letters: this.letters
            });
            console.log({data});

            if(data.error){
                throw data.error;
            }

            this.letters = data.purchase.letters;
            this.purchase.id = data.purchase.purchase_id;
            this.isLoading = false;

            history.pushState(null, null, "#download-nft")
            this.route = "#download-nft";
            this.isSaved = true;

        },

        async creditcard(){
            if (this.isValid()) {
                this.isLoading = true;
                try {
                    const payment_results = await pay_stripe(stripe, this.card_el);
                    console.log({payment_results});
                    
                    this.purchase.cardNumber = payment_results.paymentMethod.card.last4;
                    this.purchase.expirationDate = payment_results.paymentMethod.card.exp_month + "/" + payment_results.paymentMethod.card.exp_year;
                    this.purchase.paymentMethodId = payment_results.paymentMethod.id;           
                    this.purchase.paid = this.price;
                    await this.submitForm();
                    await this.mintToBurner();
                } catch (error) {
                    console.error(error);
                    toastError(error.message ?? error);
                    this.isLoading = false;
                    return;
                }
            }
        },

        setupPayPalButtons(){
            /*global paypal*/ //    injected by paypals hosted script
            const vm = this;
            paypal.Buttons({
                createOrder: (data, actions) => {
                    console.log({paypal_data: data});
                    if (!vm.isValid()) {
                        return actions.reject();
                    }
        
                  return actions.order.create({
                    purchase_units: [{
                      amount: { value: vm.price },
                      description: vm.letters.length + " letters in a special Torah Scroll",
                      soft_descriptor: "NFTorah Letters",
                      quantity: vm.letters.length
                    }],
                    application_context: {
                        shipping_preference: 'NO_SHIPPING',
                    }
                
                  });
                },
                onApprove: async function(data, actions) {
                    const details = await actions.order.capture();
                    console.log({ details });

                    //vm.purchase.cardNumber = "PAYPAL";
                    vm.purchase.cardNumber = details.id;
                    vm.purchase.paymentMethodId = details.id;
                    vm.purchase.paid = vm.price;
                    try {
                        await vm.submitForm();
                        await vm.mintToBurner();                        
                    } catch (error) {
                        vm.reportError(error, "PayPal");
                    }
                }
            }).render('#paypal-button-container'); // Display payment options on your web page
        },

        async cryptoPayment(){
            const vm = this;

            if(!window.ethereum){
                toastError("You don't have a crypto wallet attached to your browser.");
            }

            if (this.isValid()) {
                this.isLoading = true;
 
                // Metamask injects as window.ethereum into each page
                await ethereum.request({ method: 'eth_requestAccounts' });
                await window.ethereum.enable()
                const mm_provider = await new ethers.providers.Web3Provider(window.ethereum);
                console.log({mm_provider});
                try {
                    const network = await mm_provider.getNetwork( );
                    if(network.chainId != PROPER_CHAIN_ID){
                        throw `Your crypto wallet is set to ${network.name}. Please set it to ${PROPER_CHAIN_NAME}`;
                    }

                    const mm_signer = mm_provider.getSigner();

                    const gwei = ethers.utils.parseEther( this.one_dollar_in_eth) * vm.price ;
                    const tx = await mm_signer.sendTransaction({
                        to: contractAddress,
                        value: gwei
                    });
                    const user_address = await mm_signer.getAddress();                    
                
                    //vm.purchase.cardNumber = "ETHER";
                    vm.purchase.cardNumber = tx.hash;
                    vm.purchase.paymentMethodId = tx.hash;
                    vm.purchase.publicAddress = tx.from;
                    vm.purchase.paid = gwei;
                    await vm.submitForm();
                    await vm.mintNFT(user_address);
                } catch (error) {
                    console.error(error);
                    toastError(error.message ?? error);
                    this.isLoading = false;
                    return;
                }

            }
        },

        async saveBurnerWallet(purchase_id, wallet_address, mnemonic, wallet_private_key){
            const response = await NFTorah_api('purchases/crypto', {
                purchase_id, wallet_address, mnemonic, wallet_private_key
            });
        },

        async mintToBurner(){
            wallet = ethers.Wallet.createRandom();
            this.mnemonic = wallet.mnemonic;
            this.wallet_address = wallet.address;

            await this.mintNFT(wallet.address);
        },

        async mintNFT(address){

            const contract = new ethers.Contract(contractAddress, abi, provider);
            const contractWithSigner = contract.connect(minter_wallet);

            const filter = contractWithSigner.filters.Transfer(null, address);
            contractWithSigner.on(filter, (from, to, value, event) => {
                console.log({ event });
                this.token_ids.push(value);
            });

            for (const letter of this.letters) {
                const tx = await contractWithSigner.mint(address, letter.id);
                console.log({tx})
            }
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

            for (const token_id of this.token_ids) {
                this.transfer_tx = await contractWithSigner.safeTransferFrom(wallet.address, this.new_address, token_id);
                this.transfer_tx = await this.transfer_tx.wait();                
            }
        },
        async encryptWallet(){
            this.json_wallet = 'processing';
            const password = prompt("Choose a password");
            const json_wallet = await wallet.encrypt( password );
            this.json_wallet = 'data:text/plain;charset=utf-8,' + encodeURIComponent(json_wallet);
        },
        reportError(error, section = null){
            const msg  = error.msg || error;
            this.validationMessages.push({ section, name: "Error", msg: msg })
        },
    },
    computed:{
        price(){ // paid
            return this.letters.length + this.verses_count * 18 + this.chapters_count * 360;
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

function roundCryptoValueString(str, decimalPlaces=18){
    const arr = (""+str).split(".");
    const fraction = arr[1] .substr(0, decimalPlaces);
    return arr[0] + "." + fraction;
}