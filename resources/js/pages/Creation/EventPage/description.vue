<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Description</p>
            </div>
            <div class="flex justify-center h-full w-full p-8 mb-96">
                <div class="max-w-3xl">
                    <h2>Describe your event to our readers</h2>
                    <div class="mt-16">
                        <p class="text-black">What will you and your guests do?</p>
                        <ul class="my-6">
                            <li>
                                Be clear and concise about what the event will entail
                            </li>
                            <li>
                                Make sure to include enough information without overwhelming
                            </li>
                        </ul>
                        <textarea 
                            type="text"
                            name="description" 
                            v-model="inputVal.event.description" 
                            @input="$v.inputVal.event.description.$touch"
                            placeholder="This is your chance to let our readers know about your event"
                            rows="8" />
                        <Errors 
                            item="description"
                            :errors="$v.inputVal.event.description" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import { required, url, maxLength } from 'vuelidate/lib/validators'
    import Errors from '../creationError.vue'
    export default {

        props: ['value'],

        components: { Errors },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                
            };
        },

        methods: {
            
        },

        validations: {
            inputVal: {
                event : {
                    description: {
                        required,
                        maxLength: maxLength(5000),
                    },
                }
            }
        },
    }
</script>