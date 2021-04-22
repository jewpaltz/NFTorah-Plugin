<div class="box" v-for="(letter, i) in letters">
                <small><b>Letter {{i+1}}</b></small>
                <button class="delete"
                        v-if="letters.length > 1"
                        @click.prevent="deleteLetter(i)"></button>
        <div class="columns">
            <div class="column">
                <label class="label" for="hebrewName">
                    Hebrew Name
                    <small>(if you know it)</small>
                </label>
                <b-field>
                    <b-input v-model="hebrewName" id="hebrewName"></b-input>
                </b-field>
            </div>
            <div class="column">
                <label class="label" for="hebrewName">
                    Secular Name
                </label>
                <b-field>
                    <b-input v-model="hebrewName" id="hebrewName"></b-input>
                </b-field>
            </div>
        </div>
        <div class="columns">
            <div class="column">
            <b-field label="Last Name">
                    <b-input v-model="hebrewName" id="hebrewName"></b-input>
                </b-field>
            </div>
            <div class="column">
                <label class="label" for="hebrewName">
                    Mother's Name
                    <small>(Hebrew if you know it)</small>
                </label>
                <b-field>
                    <b-input v-model="hebrewName" id="hebrewName"></b-input>
                </b-field>
            </div>
        </div>
    </div>
    <b-button  type="is-primary is-light" expanded @click.prevent="addLetter">Add Another Letter</b-button>
