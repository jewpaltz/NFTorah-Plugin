<div class="box" style="padding: .5rem; margin-bottom: .5rem;" section="Verses & Sections" >
        <div class="columns" style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Hebrew Name" label-position="inside" message="It's best to provide the Hebrew Name if you know it">
                    <b-input v-model="verses.hebrewName" name="Hebrew Name" ></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Secular Name" label-position="inside" message="Required, if you don't provide a Hebrew Name.">
                    <b-input v-model="verses.secularName" name="Secular Name" :required="(verses_count + chapters_count) > 0 && !verses.hebrewName"></b-input>
                </b-field>
            </div>
        </div>
        <div class="columns"  style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
            <b-field label="Last Name"  label-position="inside" >
                    <b-input v-model="verses.lastName" name="Last Name" :required="(verses_count + chapters_count) > 0"></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field  label="Mother's Name" label-position="inside" message="Hebrew if you know it, secular if you don't">
                    <b-input v-model="verses.mothersName" name="Mothers Name" :required="(verses_count + chapters_count) > 0"></b-input>
                </b-field>
            </div>
        </div>
        <div class="columns is-compact"  style="margin: 0;">
            <div class="column" style="padding-top: 0px; padding-bottom: 0.25rem;">

                <b-field>
                    <p class="control">
                        <label class="button is-static is-medium">Verses</label>
                    </p>
                    <b-input type="number" v-model="verses_count" expanded size="is-medium"></b-input>
                    <p class="control">
                        <span class="button is-static is-medium">x $18 = ${{verses_count * 18}}</span>
                    </p>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0px; padding-bottom: 0.25rem;">

                <b-field>
                    <p class="control">
                        <label class="button is-static is-medium">Chapters</label>
                    </p>
                    <b-input  type="number" v-model="chapters_count" expanded size="is-medium"></b-input>
                    <p class="control">
                        <span class="button is-static is-medium">x $360 = ${{chapters_count * 360}}</span>
                    </p>
                </b-field>
            </div>
        </div>
        
    </div>
