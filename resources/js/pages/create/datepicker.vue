<template>
    <div>
        
            Opening Date of the Event
           
            <v-date-picker
            mode='multiple'
            is-inline
            v-model='selectedValue'>
            </v-date-picker>
            <div v-if="$v.selectedValue.$error" class="validation-error">
                <p class="error" v-if="!$v.selectedValue.required">Please select at least one Date</p>
            </div>
     

            <button 
            @click.prevent="submitDates()" 
            class="create"> 
                Next 
            </button>

    </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';


export default {
    props: {
        event: {
            type:Object,
        },
    },  

    data() {
        return {
            eventUrl:_.has(this.event, 'slug') ? `/create/${this.event.slug}` : null,
            selectedValue: new Date(),
        }
    },

    methods: {



        async submitDates() {

         	this.$v.$touch();
			if (this.$v.$invalid) { return false }

            let data = {
                'dates': this.selectedValue,
            };

            axios.patch(`${this.eventUrl}/dates`, data)
            .then(response => {
                   console.log('worked')
            })
            .catch(errorResponse => { 
                   console.log('error');
            });
        },

    },

    mounted() {

        //This adds the dates from database into date picker
        this.selectedValue = this.event.dates.map(a => new Date(a.date));

    },

    validations: {
			selectedValue: {
				required,
			},
		},
}  
</script>