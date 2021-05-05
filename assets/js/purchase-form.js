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
}

const formVue = new Vue({
    el: '#postbox',
    data: ()=>({
        letters: [new Letter()],
        activeItemTab: "1",
        activePaymentTab: "1",
        isSaved: false,
        purchase: new Purchase(),
        route: window.location.hash
    }),
    mounted(){
        
    },
    methods: {
        addLetter(){
            this.letters.push(new Letter());
        },
        deleteLetter(i){
            this.letters.splice(i,1);
        },
        creditcard(){
            //  Charge Credit Card
            this.trySubmitForm();
        },
        paypal(){
            //  Show Paypal UI
            this.purchase.cardNumber = "PayPal";
            this.trySubmitForm();
         },
         trySubmitForm(){
            if (this.isValid()) {
                this.submitForm();
              } else {
                  for (const field of this.$refs.form) {
                      if(field.blur){
                          field.dispatchEvent(new Event('blur'));
                      }
                  }
                this.$refs.form.reportValidity();
              }
         },
         isValid(){
            if(!this.$refs.form.checkValidity()){
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

            history.pushState(null, null, "#download-nft")
            this.route = "#download-nft";
            this.isSaved = true;

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
    }
});       


window.addEventListener('hashchange', function() {
    formVue.route = window.location.hash;
  }, false);