<template>
    <div class="community-index">
        <div class="add-community">
            <a href="/communities/create">
                <button>
                    Add New Community
                </button>
            </a>
        </div>
        <div class="table">
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p>Status</p>
                    <p>Community</p>
                    <p>Last Modified</p>
                </div>
                <div
                    class="data-grid__row"
                    v-for="(community, index) in communities"
                    :key="community.id">
                    <div class="center">
                        <div
                            @mouseover="active = index" 
                            @mouseleave="active = ''"
                            :style="getStatusCircle(community)"
                            class="status-circle" />
                        <div 
                            v-if="active === index"
                            class="hover-box">
                            <p>{{ getStatusDetail(community) }}</p>
                        </div>
                    </div>
                    <div class="lg">
                        <template v-if="locked(community)">
                            <img 
                                v-if="community.thumbImagePath"
                                :src="`/storage/${community.thumbImagePath}`">
                            <p v-if="community.name">{{ community.name }}</p>
                            <p v-else>New Event</p>
                        </template>
                        <template v-else>
                            <a 
                                target="_blank"
                                :href="`/communities/${community.slug}`">
                                <img 
                                    v-if="community.thumbImagePath"
                                    :src="`/storage/${community.thumbImagePath}`">
                                <p v-if="community.name">{{ community.name }} 
                                    <span 
                                        style="text-decoration: underline;" 
                                        v-if="community.status === 'p'">
                                        (edit community)
                                    </span>
                                </p>
                                <p v-else>New Event</p>
                            </a>
                        </template>
                    </div>
                    <div>
                        <p>{{ cleanDate(community.updated_at) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: ['user', 'value' ],

        computed: {
            locked() {
                return community => this.isLocked(community)
            },
            getStatus() {
                return community => this.status(community)
            },
            getStatusCircle() {
                return community => this.statusCircle(community)
            },
            getStatusDetail() {
                return community => this.statusDetail(community)
            },
        },

        data() {
            return {
                communities: this.value,
                active: null,
            }
        },

        methods: {
            async deleteCommunity(community) {
                await axios.delete(`/communities/${community.slug}`)
                .then( res => {
                    console.log(res.data);
                })
            },
            status(event) {
                if (event.status === 'p') return 'approved';
                if (event.status === 'r') return 'under moderator review';
                if (event.status === 'n') return 'revise and resubmit';
                return '-'
            },
            statusDetail(event) {
                if (event.status === 'p') return 'Approved';
                if (event.status === 'r') return 'Under review';
                if (event.status === 'n') return 'Fix moderator notes';
                return '-'
            },
            statusCircle(community) {
                if (community.status === 'd') return `background: #1bbb1b`;
                if (community.status === 'r') return `background: black`;
                if (community.status === 'n') return `background: #bf1515`;
                return `background: #bf1515`;
            },
            isLocked(event) {
                if (event.status === 'r') return true;
            },
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD, YYYY");
            },
        }

    }
</script>