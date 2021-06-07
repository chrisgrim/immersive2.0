<template>
    <div>
        <div class="event-create__submit-button">
            <button 
                :disabled="disabled" 
                @click.prevent="onBackInitial()" 
                class="nav-back-button"> 
                Dashboard
            </button>
        </div>

        <div 
            v-if="newsubmission" 
            class="create-button__forward">
            <button 
                :disabled="disabled" 
                class="create" 
                @click.prevent="acceptWarning"> 
                Continue
            </button>
        </div>
        
        <div v-else>
            <div v-if="!approved">
                <div class="create-button__back">
                    <button 
                        :disabled="disabled" 
                        class="create" 
                        @click.prevent="onBack(previous)"> 
                        Back 
                    </button>
                </div>
                <div 
                    class="create-button__forward"
                    v-if="next=='review'">
                    <button 
                        :disabled="ready" 
                        class="create" 
                        @click.prevent="submit"> 
                        Review 
                    </button>
                </div>
                <div
                    v-else
                    class="create-button__forward">
                    <button 
                        :disabled="disabled" 
                        class="create" 
                        @click.prevent="submit"> 
                        Save and continue 
                    </button>
                </div>
            </div>
            <div v-else>
                <template v-if="approved">
                    <template v-if="renew">
                        <div class="create-button__forward">
                            <button 
                                :disabled="disabled" 
                                class="create" 
                                @click.prevent="renewDates()"> 
                                Save and Renew
                            </button>
                        </div>
                    </template>
                    <template v-else>
                        <div class="create-button__forward">
                            <button 
                                :disabled="disabled" 
                                class="create" 
                                @click.prevent="save()"> 
                                Save 
                            </button>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>

<script>

import formValidationMixin from '../../../mixins/form-validation-mixin'

    export default {

        props: ['event','next', 'previous', 'disabled','newsubmission', 'ready'],

        mixins: [formValidationMixin],

        computed: {
            renew() {
                if (!this.event.shows.length) {return}
                if (this.next !== 'tickets') {return}
                if (this.event.showtype === 's' || this.event.showtype === 'l') {return}
                    
                const lastShowDate = this.event.shows[0].date;
                const oneMonthOut = this.$dayjs().add(30, 'day').format('YYYY-MM-DD 23:59:00')
                
                return lastShowDate < oneMonthOut ? true : false
            }
        },

        data() {
            return {
               approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', this.next);
            },

            save() {
                this.$emit('submit', 'save');
            },

            renewDates() {
                this.$emit('renewDates', 'save');
            },

            acceptWarning() {
                this.$emit('newevent', true);
            },

        }

    }
</script>