class Letter {
    hebrewName = "";
    secularName = "";
    lastName = "";
    mothersName = "";
}

class Purchase {
    firstName = null;
    lastName = null;
    email = null;
    phone = null;
    cardNumber = null;
    expirationDate = null;
    cvv = null;
}

new Vue({
    el: '#postbox',
    data: ()=>({
        letters: [new Letter()],
        activeItemTab: "1",
        activePaymentTab: "1",
        isSaved: false,
        purchase: new Purchase()
    }),
    methods: {
        addLetter(){
            this.letters.push(new Letter());
        },
        deleteLetter(i){
            this.letters.splice(i,1);
        },
        creditcard(){
            //  Charge Credit Card
            this.submitForm();
        },
        paypal(){
            //  Show Paypal UI
            this.purchase.cardNumber = "PayPal";
            this.submitForm();
         },
        async submitForm(){
            this.purchase.paid = this.price;
            const data = await  NFTorah_api('purchases', {
                purchase: this.purchase,
                letters: this.letters
            });
            console.log({data});

            history.pushState(null, null, "#download-nft")
            this.isSaved = true;

        }
    },
    computed:{
        price(){ // paid
            return this.letters.length;
        },
        page(){
            return location.hash.includes("download-nft")  ? //|| this.isSaved
                2 :
                1 ;
        },
        a_letter(){
            return this.letters.length == 1 ? "a letter" : this.letters.length + " letters"
        }
    }
});       

