<template>
    <section class="py-12 px-8 md:py-16 md:px-0">
        <div>
            <div class="title first">
                <h3 class="font-semibold md:font-medium">Audience Role</h3>
            </div>     
            <div class="my-8">
                <ShowMore 
                    :text="event.advisories.audience"
                    :limit="30" />
            </div> 
        </div>

        <div>
            <div>
                <h3 class="font-semibold md:font-medium">Content Advisories</h3>
            </div>
            <ul class="my-8 ml-16 list-disc">
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
                <h3 class="font-semibold md:font-medium">Interaction Advisories</h3>
            </div>
            <ul class="my-8 ml-16 list-disc">
                <li 
                    v-for="item in event.contact_levels"
                    :key="item.id">
                    <p> {{ item.level }} </p>
                </li>
            </ul>
        </div>

        <div>
            <div>
                <h3 class="font-semibold md:font-medium">Mobility Advisories</h3>
            </div>
            <ul class="my-8 ml-16 list-disc">
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
                <h3 class="mb-4">Sexual Advisories</h3>
            </div>
            <ul class="info">
                <li>
                    <p> {{ event.advisories.sexualDescription }} </p>
                </li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4">Tags</h3>
            <div
                class="inline-block rounded-md border border-slate-200 mr-4 mt-4 px-4"
                :class="[item.admin == 1 ? 'cursor-pointer hover:bg-gray-400 hover:text-white hover:border-white' : '' ]"
                :key="item.id"
                v-for="item in event.genres">
                <a 
                    v-if="item.admin == 1" 
                    :href="`/index/search-all?tag=${item.name}`"><b><span> {{ item.name }} </span></b></a>
                <span v-else> 
                    {{ item.name }} 
                </span>
            </div>
        </div>
    </section>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
    export default {

        props: [ 'event' ],
        components: { ShowMore },
    }
</script>