<template>
    <section class="es__details pad4">
        <div>
            <div class="title">
                <h3>Audience Role</h3>
            </div>      
            <ShowMore 
                :text="event.advisories.audience"
                :limit="30" />
        </div>

        <div>
            <div>
                <h3>Content Advisories</h3>
            </div>
            <ul>
                <li 
                    v-for="item in event.content_advisories"
                    :key="item.id">
                    <p> {{ item.advisories }} </p>
                </li>
                <li>
                    <p> {{ event.age_limits ? event.age_limits.name : event.advisories.ageRestriction }} </p>
                </li>
            </ul>
        </div>

        <div>
            <div>
                <h3>Interaction Advisories</h3>
            </div>
            <ul>
                <li 
                    v-for="item in event.contact_levels"
                    :key="item.id">
                    <p> {{ item.level }} </p>
                </li>
            </ul>
        </div>

        <div>
            <div>
                <h3>Mobility Advisories</h3>
            </div>
            <ul>
                <li 
                    v-for="item in event.mobility_advisories"
                    :key="item.id">
                    <p> {{ item.mobilities }} </p>
                </li>
                <li>
                    <p>Event is <span v-if="!event.advisories.wheelchairReady">not</span> wheelchair accessible.</p>
                </li>
            </ul>
        </div>

        <div v-if="event.advisories.sexual">
            <div class="title">
                <h3>Sexual Advisories</h3>
            </div>
            <ul class="info">
                <li>
                    <p> {{ event.advisories.sexualDescription }} </p>
                </li>
            </ul>
        </div>

        <div>
            <h3>Tags</h3>
            <div class="es__tags">
                <div
                    class="es__tags--element"
                    :key="item.id"
                    v-for="item in event.genres">
                    <a 
                        v-if="item.admin == 1" 
                        :href="`/index/search-all?tag=${item.name}&id=${item.id}`"><b><p> {{ item.name }} </p></b></a>
                    <p v-else> 
                        {{ item.name }} 
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import ShowMore  from '../components/show-more.vue'
    export default {

        props: [ 'event' ],

        components: { ShowMore },

        data() {
            return {
                
            }
        },
    }
</script>