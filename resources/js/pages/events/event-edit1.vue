<template>
    <div>
        <template v-if="organizerList && organizerList.length">
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p></p>
                    <p>Organization</p>
                    <p>Owner</p>
                    <p>Members</p>
                    <p></p>
                </div>
                <div 
                    class="data-grid__row"
                    v-for="(org) in organizers"
                    :key="org.id">
                    <div class="edit">
                        <a :href="`/organizer/${org.slug}/edit`">
                            <IconSvg type="edit" />
                        </a>
                    </div>
                    <div>
                        <img :src="`/storage/${org.thumbImagePath}`">
                        <p>{{ org.name }}</p>
                    </div>
                    <div>
                        <button 
                            class="noBox" 
                            @click.prevent="showModal(org, 'changeOwner')">
                            <p>{{ org.user.name }}</p>
                        </button>
                    </div>
                    <div>
                        <v-select 
                            v-model="org.users"
                            label="name"
                            placeholder="Members"
                            multiple
                            @search="onSearchUsers"
                            @input="addNewMember($event, org)"
                            :clearable="false"
                            :options="users.length ? users : org.users" />
                    </div>
                    <button 
                        @click.prevent="showModal(org, 'deleteOrg')" 
                        class="delete">
                        <IconSvg type="delete" />
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: ['user'],

        data() {
            return {
                organizerList: [],
            }
        },

        methods: {
            async onload() {
                await axios.get(`/create/organizers/fetch?timestamp=${new Date().getTime()}`)
                .then(res => {
                    this.organizerList = res.data;
                })
            },
        },

        mounted() {
            this.onload();
        },
    }
</script>