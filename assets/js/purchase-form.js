new Vue({
    el: '#purchase-form',
    data: ()=>({
        letters: [{}],
    }),
    methods: {
        addLetter(){
            this.letters.push({});
        },
        deleteLetter(i){
            this.letters.splice(i,1);
        }
    },
    computed:{
        price(){
            return this.letters.length;
        }
    }
});       
