<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Title</p>
            </div>
            <div class="flex justify-center h-full w-full p-8 mb-96">
                <div class="max-w-3xl">
                    <h2 class="mb-4">Give your event a name</h2>
                    <p class="font-light text-3xl mb-16">Make it clear and descriptive so it will catch our reader's eye.</p>
                    <div class="mb-8">
                        <textarea 
                            type="text"
                            name="title" 
                            class="text-5xl border-black font-bold leading-normal" 
                            v-model="inputVal.event.name" 
                            @input="checkStatus"
                            placeholder="Make it a good one!"
                            rows="4" />
                        <Errors 
                            item="name"
                            :errors="$v.inputVal.event.name" />
                    </div>
                    <h4 class="text-black text-2xl">Lets add a tag line while we are at it.</h4>
                    <div>
                        <textarea 
                            name="Tag Line"
                            placeholder="Tag Line" 
                            class="border-black" 
                            v-model="inputVal.event.tag_line"
                            @input="checkStatus"
                            rows="2"
                            type="text" />
                        <Errors 
                            item="Tag Line"
                            :errors="$v.inputVal.event.tag_line" />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import { required, maxLength, minLength } from 'vuelidate/lib/validators'
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
            checkStatus() {
                this.$v.$touch()
                this.inputVal.submit =! this.$v.$invalid
            }
        },

        mounted() {
            this.checkStatus();
        },

        validations: {
            inputVal: {
                event : {
                    name: {
                        required,
                        maxLength: maxLength(60),
                    },
                    tag_line: {
                        required,
                        // minLength: minLength(10),
                        maxLength: maxLength(200),
                    },
                }
            }
        },
    }
</script>