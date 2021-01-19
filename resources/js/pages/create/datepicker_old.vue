<template>
    <div>
        <div class="dates-grid">
            <div>
                Opening Date of the Event
                <div class="datepicker-trigger ">
                  <input
                    id="datepicker-trigger"
                    placeholder="Opening Date"
                    :value="formatDates(dateOne)"
                    type="hidden" 
                    name="openingDate">
                    <br>

                  <AirbnbStyleDatepicker
                    :trigger-element-id="'datepicker-trigger'"
                    :inline="true"
                    :mode="'single'"
                    :key="1"
                    :months-to-show="1"
                    :fullscreen-mobile="true"
                    :date-one="dateOne"
                    @date-one-selected="val => { dateOne = val }"
                  />
                </div>

                <div v-if="$v.dateOne.$error" class="validation-error">
	    				<p class="error" v-if="!$v.dateOne.required">The Opening Date is required</p>
	    		</div>

            </div>
            <div>
            Closing Date
                <div class="datepicker-trigger">
                  <input
                    type="hidden" 
                    id="datepicker-trigger1"
                    placeholder="Closing Date"
                    :value="formatDatesA(dateTwo)"
                    name="closingDate">
                    <br>

                  <AirbnbStyleDatepicker
                    :trigger-element-id="'datepicker-trigger1'"
                    :mode="'single'"
                    :inline="true"
                    :key="2"
                    :months-to-show="1"
                    :fullscreen-mobile="true"
                    :date-one="dateTwo"
                    @date-one-selected="val => { dateTwo = val }"
                  />
                </div>

                 <div v-if="$v.dateTwo.$error" class="validation-error">
	    			<p class="error" v-if="!$v.dateTwo.required">The Closing Date is required</p>
	    		</div>
            </div>
        </div>


        <div>
        	<button @click.prevent="calRange()">Date Range</button> {{dateRange}}
        </div>


        <div class="">
            <button 
            @click.prevent="submitDates()" 
            class="create"> 
        		Next 
        	</button>
        </div>


    </div>
</template>

<script>
import format from 'date-fns/format';
import { required } from 'vuelidate/lib/validators';


export default {
    props: {
        event: {
            type:Object,
        },
    },  

    data() {
        return {
            dateFormat: 'D MMM',
            dateOne: '',
            dateTwo: '',
            eventUrl:_.has(this.event, 'slug') ? `/create/${this.event.slug}` : null,
            dateRange: '',
        }
    },

    methods: {

        formatDates(dateOne) {
            let formattedDates = ''

            if (dateOne) {
                    formattedDates = format(dateOne, this.dateFormat)
            }
            return formattedDates
        },

        formatDatesA(dateTwo) {
            let formattedDates = ''

            if (dateTwo) {
                formattedDates = format(dateTwo, this.dateFormat)
            }
            return formattedDates
        },

        calRange() {
        	this.dateRange = this.dateTwo - this.dateOne;
        },


        async submitDates() {

        	this.$v.$touch();
			if (this.$v.$invalid) { return false }

            let data = {
            'openingDate': this.dateOne,
            'closingDate': this.dateTwo,
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
        this.dateOne = this.event.date.openingDate;
        this.dateTwo = this.event.date.closingDate;
    },

    validations: {
			dateOne: {
				required,
			},
			dateTwo: {
				required
			}
		},
}  
</script>