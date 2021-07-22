<div class="box" style="padding: .5rem; margin-bottom: .5rem;" v-for="(letter, i) in letters" :section="`Letter ${i+1}`" >
                <small>&nbsp;<b>Letter {{i+1}}</b></small>
                <button class="delete"
                        v-if="letters.length > 1 || (verses_count + chapters_count) > 0"
                        @click.prevent="deleteLetter(i)"></button>
        <div class="columns" style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Hebrew Name" label-position="inside" message="It's best to provide the Hebrew Name if you know it">
                    <b-input v-model="letter.hebrewName" name="Hebrew Name" ></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Secular Name" label-position="inside" message="Required, if you don't provide a Hebrew Name.">
                    <b-input v-model="letter.secularName" name="Secular Name" :required="!letter.hebrewName" ></b-input>
                </b-field>
            </div>
        </div>
        <div class="columns"  style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
            <b-field label="Last Name"  label-position="inside" >
                    <b-input v-model="letter.lastName" name="Last Name" required ></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field  label="Mother's Name" label-position="inside" message="Hebrew if you know it, secular if you don't">
                    <b-input v-model="letter.mothersName" name="Mothers Name" required ></b-input>
                </b-field>
            </div>
        </div>
    </div>
    <b-button  type="is-primary is-light is-outlined" expanded @click.prevent="addLetter">Add Another Letter</b-button>
