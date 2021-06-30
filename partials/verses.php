<div class="box" style="padding: .5rem; margin-bottom: .5rem;" >
        <div class="columns" style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Hebrew Name" label-position="inside" message="It's best to provide the Hebrew Name if you know it">
                    <b-input v-model="verses.hebrewName" id="hebrewName" ></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field label="Secular Name" label-position="inside" message="Required, if you don't provide a Hebrew Name.">
                    <b-input v-model="verses.secularName" id="secularName" :required="!verses.hebrewName"></b-input>
                </b-field>
            </div>
        </div>
        <div class="columns"  style="margin: 0;">
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
            <b-field label="Last Name"  label-position="inside" >
                    <b-input v-model="verses.lastName" id="lastName" required></b-input>
                </b-field>
            </div>
            <div class="column" style="padding-top: 0; padding-bottom: .25rem;">
                <b-field  label="Mother's Name" label-position="inside" message="Hebrew if you know it, secular if you don't">
                    <b-input v-model="verses.mothersName" id="mothersName" required></b-input>
                </b-field>
            </div>
        </div>

        <table class="table">
        <tr class="table-form">
            <td>
                <label class="label field-label">Verses</label>
            </td>
            <td>$18 x</td>
            <td><div class="control">
                    <input class="input " type="number" v-model="verses_count">
                </div></td>
            <td>= ${{model_count * 18}}</td>
        </tr>
        </table>

        <div class="field is-horizontal">
                
                <p></p>
                
                <p></p>
        </div>
    </div>
