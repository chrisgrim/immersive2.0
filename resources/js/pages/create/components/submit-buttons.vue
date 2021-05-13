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
                    <div class="create-button__forward">
                        <button 
                            :disabled="disabled" 
                            class="create" 
                            @click.prevent="save()"> 
                            Save 
                        </button>
                    </div>
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

            acceptWarning() {
                this.$emit('newevent', true);
            }
        }

    }
</script>