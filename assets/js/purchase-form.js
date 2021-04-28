new Vue({
    el: '#postbox',
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
        },
        page(){
            return location.hash.includes("page2") ? 2 : 1;
        },
        a_letter(){
            return this.letters.length == 1 ? "a letter" : this.letters.length + " letters"
        }
    }
});       
