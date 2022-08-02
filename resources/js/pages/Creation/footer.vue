<template>
    <div>
        <div class="z-[10001] w-[calc(100vw-26rem)] left-[26rem] fixed bg-white h-28 bottom-0 border-t">
            <div class="h-full py-4 px-8 flex items-center justify-between">
                <button 
                    @click="back"
                    class="border-black border rounded-2xl font-semibold text-xl px-8 py-3">
                    Back
                </button>
                <div>
                    <p class="font-light text-lg">12 items left to submit</p>
                </div>
                <div>
                    <button 
                        :disabled="!value.submit"
                        @click="onSubmit"
                        class="bg-black text-white rounded-2xl border-0 font-semibold text-xl px-8 py-3 disabled:bg-gray-300">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['value'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            nextPage() {
                return this.value.pages[(this.value.pages.indexOf(this.value.page) + 1)]
            },
            previousPage() {
                return this.value.pages[(this.value.pages.indexOf(this.value.page) - 1)]
            },
        },

        data() {
            return {
            };
        },

        methods: {
            async onSubmit() {
                this.checkPage()
                await axios.patch(`/experience-hosting/listing/${this.value.event.slug}`, 
                    {event: this.value.event})
                .then( res => { 
                    this.checkList()
                    this.checkCategory()
                    this.inputVal.page = this.nextPage
                });
            },
            checkCategory() {
                this.inputVal.category = ''
            },
            checkPage() {
                if (this.value.page === 'Overview' && Number(this.value.event.status) === 0) {this.inputVal.event.status = 1}
            },
            back() {
                this.inputVal.category = ''
                this.inputVal.page = this.previousPage
            },
            checkList() {
                this.inputVal.pages = this.value.event.hasLocation ? 
                ['EventType','Category','Overview','Description','EventHost','Location','GuestRequirements','Title','Photo','EventType','EventType'] : 
                ['EventType','Category','Overview','Description','EventHost','GuestRequirements','Title','Photo','EventType','EventType']
            }
        },


    };
</script>