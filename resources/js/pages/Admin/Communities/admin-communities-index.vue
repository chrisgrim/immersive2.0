<template>
    <div>
        <h2 class="mb-8">Communities</h2>
        <div class="field">
            <input 
                v-model="communityList"
                placeholder="Filter by event name" 
                class="general"
                @keyup="debounce(communityList)"
                type="text">
        </div>

        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    v-for="col in cols"
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr
                                :key="community.id"
                                v-for="community in communities.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        target="_blank"
                                        class="rounded-full w-12 h-12 block flex items-center justify-center hover:bg-black" 
                                        :href="`/communities/${community.slug}/edit`">
                                        <svg class="w-8 h-8 hover:fill-white">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-edit-2-line`" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        class="flex flex-col items-center" 
                                        target="_blank"
                                        :href="`/communities/${community.slug}`">
                                        <img 
                                            class="w-32 rounded-2xl" 
                                            v-if="community.thumbImagePath"
                                            :src="`${$envImageUrl}${community.thumbImagePath}`">
                                        <p class="text-xl text-center">{{ community.name }}</p>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p class="text-xl">
                                        {{ community.owner.name}}
                                    </p>
                                </td>
                                
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p 
                                        :key="curator.id"
                                        v-for="curator in community.curators"
                                        class="text-xl">
                                        {{ curator.name }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="communities"
            @selectpage="onLoad" />
    </div>
</template>

<script>
    import Pagination  from '../../../components/pagination.vue'
    export default {
        props:['value'],

        components: { Pagination },

        data() {
            return {
                communities: this.value,
                communityList: '',
                modalData: null,
                modal: '',
                page:1,
                cols: [
                    { id:0, field: '', class:''},
                    { id:1, field: 'Name', class:'text-center'},
                    { id:2, field: 'Owner', class:''},
                    { id:3, field: 'Curators', class:'' },
                ],
            }
        },

        methods: {
            async onSearch(events) {
                await axios.get('/api/admin/communities/search', { params: { keywords: events } })
                .then( res => { this.communities = res.data })
            },
            async onLoad(page) {
                await axios.get(`/api/admin/communities/search?page=${page}`)
                .then( res => { this.communities = res.data })
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.onSearch(query)
                }, 200); // delay
            },
        },

    }

</script>