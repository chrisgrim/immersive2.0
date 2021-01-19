<template>
    <div>
        <div 
            v-if="!mobile"
            class="filter__content">
            <div
                v-if="hasLocation" 
                class="filter__grid">
                <!-- Location Filter -->
                <div class="filter__location">
                    <div v-if="url.lat">
                        <button @click="clearLocation">
                            <svg 
                                viewBox="0 0 12 12" 
                                role="presentation" 
                                aria-hidden="true" 
                                focusable="false" 
                                style="height: 8px; width: 8px; display: block;">
                                <path d="m11.5 10.5c.3.3.3.8 0 1.1s-.8.3-1.1 0l-4.4-4.5-4.5 4.5c-.3.3-.8.3-1.1 0s-.3-.8 0-1.1l4.5-4.5-4.4-4.5c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l4.4 4.5 4.5-4.5c.3-.3.8-.3 1.1 0s .3.8 0 1.1l-4.5 4.5z" />
                            </svg>
                        </button>
                    </div>
                    <vue-nav-search :searchtype="searchType" />
                </div>
                <!-- Date Filter -->
                <div class="filter__dates">
                    <div ref="dates">
                        <button 
                            @click="show('dates')" 
                            :class="{ active : naturalDate.length }" 
                            class="filter">
                            <template v-if="displayDate">
                                {{ naturalDate[0] }}{{ naturalDate[1] ? ' | ' + naturalDate[1] : '' }}
                            </template>
                            <template v-else>
                                Start Date | End Date
                            </template>
                        </button>
                    </div>
                </div>
                <!-- Price Filter -->
                <div class="filter__price">
                    <vue-slider
                        v-model="price"
                        tooltip="always"
                        v-bind="priceOptions"
                        :tooltip-formatter="sliderFormat"
                        @drag-end="submit"
                        :enable-cross="false" />
                </div>
            </div>
            <div class="filter__block">
                <!-- Date Filter -->
                <div 
                    v-if="!hasLocation" 
                    class="filter__categories">
                    <div ref="dates">
                        <button 
                            @click="show('dates')" 
                            :class="{ active : naturalDate.length }" 
                            class="filter round">
                            <template v-if="displayDate">
                                {{ naturalDate[0] }}{{ naturalDate[1] ? ' | ' + naturalDate[1] : '' }}
                            </template>
                            <template v-else>
                                Start Date | End Date
                            </template>
                        </button>
                    </div>
                </div>
                <!-- Price Filter -->
                <div 
                    v-if="!hasLocation" 
                    class="filter__categories">
                    <div>
                        <vue-slider
                            v-model="price"
                            tooltip="always"
                            v-bind="priceOptions"
                            @drag-end="submit"
                            :tooltip-formatter="sliderFormat"
                            :enable-cross="false" />
                    </div>
                </div>
                <!-- Category Filter -->
                <div class="filter__categories">
                    <div ref="cat">
                        <button 
                            @click="show('category')" 
                            :class="{ active: category.length }" 
                            class="filter round">
                            <template v-if="category.length">
                                <span 
                                    v-for="(cat) in category" 
                                    :key="cat.id" >
                                    {{ cat.name }}
                                </span>
                            </template>
                            <template v-else>
                                Categories
                            </template>
                        </button>
                    </div>
                </div>
                <!-- Tag Filter -->
                <div class="filter__tags">
                    <div ref="tag">
                        <button 
                            @click="show('tag')" 
                            :class="{ active : tag.length }" 
                            class="filter round">
                            <template v-if="tag.length">
                                <span 
                                    v-for="(item) in tag" 
                                    :key="item.id" >
                                    {{ item.name }},
                                </span>
                            </template>
                            <template v-else>
                                Tags
                            </template>
                        </button>
                    </div>
                </div>
                <template v-if="active == 'dates'">
                    <div class="filter__dates">
                        <div class="filter__dropdown">
                            <div>
                                <flat-pickr
                                    v-model="dates"
                                    :config="calendarConfig"                                         
                                    placeholder="Select date"               
                                    name="dates" />
                            </div>
                            <div class="filter__dropdown--footer">
                                <button 
                                    v-if="naturalDate.length" 
                                    @click="naturalDate = []; computerDate = []; dates = [];" 
                                    class="left-half">
                                    Reset
                                </button>
                                <button 
                                    v-if="!naturalDate.length" 
                                    @click="active = null" 
                                    class="left-half">
                                    Cancel
                                </button>
                                <button 
                                    class="right-half" 
                                    @click="submit()">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="active === 'category'">
                    <div class="filter__dropdown"> 
                        <div class="filter__dropdown--grid">
                            <div 
                                v-for="(cat, index) in categories" 
                                :key="cat.id" 
                                class="filter__category--element" 
                                @click="submitCat(cat, index)">
                                <button :class="{ active: category.includes(cat) }">
                                    {{ cat.name }}
                                </button>
                            </div>
                        </div>
                        <div class="filter__dropdown--footer">
                            <button 
                                v-if="category" 
                                @click="category = ''" 
                                class="filter round">
                                clear
                            </button>
                            <button 
                                v-else 
                                @click="active = null;" 
                                class="filter round">
                                Cancel
                            </button>
                        </div>
                    </div>
                </template>
                <template v-if="active === 'tag'">
                    <div class="filter__dropdown">
                        <div class="filter__dropdown--grid">
                            <div 
                                v-for="(item) in tags" 
                                :key="item.id" 
                                class="filter__category--element" 
                                @click="submitTag(item)">
                                <div>
                                    <button :class="{ active: tag.includes(item) }">
                                        {{ item.name }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="filter__openbox--footer">
                            <button 
                                v-if="tag" 
                                @click="tag = ''" 
                                class="filter__openbox--button">
                                clear
                            </button>
                            <button 
                                v-if="!tag" 
                                @click="active = null;" 
                                class="filter__openbox--button">
                                Cancel
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Mobile Filter List -->
        <div 
            class="filter__container" 
            v-if="showFilter">
            <div class="filter__nav">
                <button 
                    @click="cancelMobile" 
                    class="mobile-filter-list__nav--close-button">
                    <svg 
                        viewBox="0 0 12 12" 
                        role="presentation" 
                        aria-hidden="true" 
                        focusable="false" 
                        style="height: 14px; width: 14px; display: block; fill: currentcolor;">
                        <path 
                            d="m11.5 10.5c.3.3.3.8 0 1.1s-.8.3-1.1 0l-4.4-4.5-4.5 4.5c-.3.3-.8.3-1.1 0s-.3-.8 0-1.1l4.5-4.5-4.4-4.5c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l4.4 4.5 4.5-4.5c.3-.3.8-.3 1.1 0s .3.8 0 1.1l-4.5 4.5z" 
                            fill-rule="evenodd" />
                    </svg>
                </button>
                <div class="mobile-filter-list__clear">
                    <button 
                        class="mobile-filter-list__clear-button" 
                        @click="clearMobile">
                        Clear
                    </button>
                </div>
            </div>
            <div class="filter__container--content">
                <!-- Mobile Price Search -->
                <div class="filter__price">
                    <h3>Price</h3>
                    <vue-slider
                        v-model="price"
                        tooltip="always"
                        v-bind="priceOptions"
                        :tooltip-formatter="sliderFormat"
                        :enable-cross="false" />
                </div>
                
                <!-- Mobile Category Search -->
                <div class="filter__categories">
                    <div>
                        <h3>Categories</h3>
                        <div class="filter__categories--top">
                            <div class="filter__categories--middle">
                                <div
                                    v-for="(cat) in categories"
                                    :key="cat.id"
                                    @click="submitMobile(category, cat)"
                                    class="filter__categories--bottom">
                                    <div
                                        :class="{ active: category.includes(cat) }"
                                        class="filter__categories--element clean-box" 
                                        style="width: 100%;height:100%">
                                        <div>
                                            <template>
                                                {{ cat.name }}
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Category Search -->
                <div class="filter__categories">
                    <div>
                        <h3>Tags</h3>
                        <div class="filter__categories--top">
                            <div class="filter__categories--middle">
                                <div
                                    v-for="(item) in tags"
                                    :key="item.id"
                                    @click="submitMobile(tag, item)"
                                    class="filter__categories--bottom">
                                    <div
                                        :class="{ active: tag.includes(item) }"
                                        class="filter__categories--element clean-box" 
                                        style="width: 100%;height:100%">
                                        <div>
                                            <template>
                                                {{ item.name }}
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile-filter-list__footer">
                <div class="mobile-filter-list__footer--button">
                    <button 
                        class="mobile-filter-button" 
                        @click="submit">
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Location Filter -->
        <div 
            class="filter__location"
            v-if="mobileLocation">
            <button @click="clearLocation">
                <svg 
                    viewBox="0 0 12 12" 
                    role="presentation" 
                    aria-hidden="true" 
                    focusable="false" 
                    style="height: 8px; width: 8px; display: block;">
                    <path d="m11.5 10.5c.3.3.3.8 0 1.1s-.8.3-1.1 0l-4.4-4.5-4.5 4.5c-.3.3-.8.3-1.1 0s-.3-.8 0-1.1l4.5-4.5-4.4-4.5c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l4.4 4.5 4.5-4.5c.3-.3.8-.3 1.1 0s .3.8 0 1.1l-4.5 4.5z" />
                </svg>
            </button>
            <div class="filter__location--back">
                <div @click="hideLocation">
                    <svg 
                        aria-label="Back" 
                        role="img" 
                        viewBox="0 0 32 32" 
                        xmlns="http://www.w3.org/2000/svg" 
                        style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 4; overflow: visible;">
                        <g fill="none">
                            <path d="m20 28-11.29289322-11.2928932c-.39052429-.3905243-.39052429-1.0236893 0-1.4142136l11.29289322-11.2928932" />
                        </g>
                    </svg>
                </div>
            </div>
            <vue-nav-search :searchtype="searchType" />
        </div>

        <!-- Mobile Dates Filter -->
        <div 
            class="filter__dates"
            v-if="mobileDates">
            <button 
                @click="hideDates"
                class="filter__dates--button">
                <svg 
                    viewBox="0 0 12 12" 
                    role="presentation" 
                    aria-hidden="true" 
                    focusable="false" 
                    style="height: 8px; width: 8px; display: block;">
                    <path d="m11.5 10.5c.3.3.3.8 0 1.1s-.8.3-1.1 0l-4.4-4.5-4.5 4.5c-.3.3-.8.3-1.1 0s-.3-.8 0-1.1l4.5-4.5-4.4-4.5c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l4.4 4.5 4.5-4.5c.3-.3.8-.3 1.1 0s .3.8 0 1.1l-4.5 4.5z" />
                </svg>
            </button>
            <h3>Dates</h3>
            <flat-pickr
                v-model="dates"
                :config="calendarConfig"                                         
                placeholder="Select date"               
                name="dates" />
            <div class="filter__dates--bottom">
                <div class="filter__dropdown--footer">
                    <button 
                        v-if="dates.length" 
                        @click="naturalDate = []; computerDate = []; dates = [];" 
                        class="left-half">
                        Reset
                    </button>
                    <button 
                        v-if="!dates.length" 
                        @click="hideDates" 
                        class="left-half">
                        Cancel
                    </button>
                    <button 
                        class="right-half" 
                        @click="submit()">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    import VueSlider from 'vue-slider-component'
    import vueNavSearch from '../../layouts/nav-search.vue'
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import 'vue-slider-component/theme/antd.css'
    
    export default {

        props:['categories','maxprice', 'events', 'onlineevents', 'tags', 'page', 'onlinepage'],

        components: { flatPickr, VueSlider, vueNavSearch },

        mixins: [ searchBasicsMixin ],

        computed: {
            displayDate() {
                return this.naturalDate.length;
            },
            data() {
                return {
                    mapboundary: this.boundaries,
                    category: this.category.map(cat => cat.id),
                    dates: this.computerDate,
                    price: this.hasPrice ? this.price : '',
                    tag: this.tag.map(item => item.name),
                    lat: this.boundaries ? '' : new URL(window.location.href).searchParams.get("lat"),
                    lng: this.boundaries ? '' : new URL(window.location.href).searchParams.get("lng"),
                    hasLocation: this.url.lat ? true : false
                }
            },
            mapInfo() {
                return this.$store.state.bounds;
            },
            showFilter() {
                return this.$store.state.filter;
            },
            mobileLocation() {
                return this.$store.state.mobilelocation;
            },
            mobileDates() {
                return this.$store.state.mobiledates;
            },
            hasLocation() {
                return this.url.lat || this.url.mapSearch;
            },
            
        },

        data() {
            return {
                active:'',
                dates: [],
                computerDate: [],
                naturalDate: [],
                calendarConfig: this.initializeCalendarObject(),
                price: [0,100],
                hasPrice: '',
                priceOptions: { min: 0, max: 100 },
                category: [],
                tag: [],
                onlinePage: 1,
                searchType: 'location',
                mobile: window.innerWidth < 768,
                boundaries: '',
                center: '',
                zoom:'',
                sliderFormat: v => `$${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`,
            }
        },

        methods: {

            submit() {
                this.reset();
                this.hasLocation ? this.axiosLoc() : this.axiosOnline();
            },

            axiosLoc() {
                axios.all([
                    axios.post(`/api/search/mapboundary?page=1`, this.data),
                    axios.post(`/api/search/online?page=1`, this.data)
                ])
                .then(axios.spread((data1, data2) => {
                    this.$emit('locationevents', data1.data);
                    this.$emit('onlineevents', data2.data);
                    this.addPushState();
                }));
            },

            axiosOnline() {
                axios.post(`/api/search/online?page=1`, this.data)
                .then(data => {
                    this.$emit('onlineevents', data);
                    this.addPushState();
                })
                .catch(err => {this.onErrors(err);});
            },

            reset() {
                this.$store.commit('onfilter', false);
                this.$store.commit('showmap', false);
                this.$store.commit('showdates', false);
                this.$store.commit('showlocation', false);
                this.active = null;
            },

            submitCat(value) {
                if (this.category.includes(value)) {
                   let removeIndex = this.category.map(function(item) { return item.id; }).indexOf(value.id);
                   this.category.splice(removeIndex, 1);
                    // this.$store.commit('searchtype', '')
                } else {
                    this.category.push(value);
                    // this.$store.commit('searchtype', value.name);
                }
                this.submit();
            },

            submitTag(value) {
                if (this.tag.includes(value)) {
                    let removeIndex = this.tag.map(function(item) { return item.id; }).indexOf(value.id);
                    this.tag.splice(removeIndex, 1);
                    // this.$store.commit('searchtype', '')
                } else {
                    this.tag.push(value);
                    // this.$store.commit('searchtype', value.name);
                }
                this.submit();
            },

            submitMobile(arg1, arg2) {
                if (arg1.includes(arg2)) {
                    let removeIndex = arg1.map(function(item) { return item.id; }).indexOf(arg2.id);
                    arg1.splice(removeIndex, 1);
                    // this.$store.commit('searchtype', '')
                } else {
                    arg1.push(arg2);
                    // this.$store.commit('searchtype', value.name);
                }
            },

            cancelMobile() {
                this.clearMobile();
                this.submit();
            },

            clearMobile() {
                this.price = [this.priceOptions.min, this.priceOptions.max]
                this.datesFormatted = [];
                this.datesSubmit = [];
                this.dates = [];
                this.category = '';
                this.tag = '';
            },

            clearLocation() {
                this.data.lat = '';
                this.data.lng = '';
                this.url.city = '';
                this.addPushState();
                location.reload();
            },

            hideLocation() {
                this.$store.commit('showlocation', false);
            },

            hideDates() {
                this.$store.commit('showdates', false);
            },

            show(type) {
                this.active === type ? this.active = null : this.active = type;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },

            dateFunc() {
            const that = this;
            return function(value) {
                that.computerDate = value.map(date => 
                    this.formatDate(date, 'Y-m-d H:i:S'));
                that.naturalDate = value.map(date => 
                    this.formatDate(date, 'D M d'));
                let shortestDates = value.map(date => 
                    this.formatDate(date, 'M d'));
                that.$store.commit('displaydates', shortestDates);
                }
            },

            initializeCalendarObject() { 
                return {
                    altFormat:'M d',
                    altInput: true,
                    mode: 'range',
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    onClose: [this.dateFunc()], 
                }
            },

            onClickOutside(event) {
                if (this.active == null) {return}
                let cat = this.$refs.cat;
                let tag = this.$refs.tag;
                let dates = this.$refs.dates;
                if (!cat || cat.contains(event.target) || !tag || tag.contains(event.target) || !dates || dates.contains(event.target)) return;
                this.active = null;
                this.submit();
            },

            conditionalBodyClass(bool, className) {
                if (bool) {
                    document.body.classList.add(className)
                    this.$store.commit('showmap', true);
                } else {
                    document.body.classList.remove(className)
                    this.$store.commit('showmap', false);
                }
            },
        },

        watch: {
            price() {
                this.hasPrice = true;
            },
            active() {
                return this.active == 'mobile' ? this.conditionalBodyClass(true, 'noscroll') : this.conditionalBodyClass(false, 'noscroll')
            },
            mapInfo() {
                this.boundaries = this.mapInfo.bounds;
                this.center = this.mapInfo.center;
                this.zoom = this.mapInfo.zoom;
                return this.submit();
            },

            page() {
                axios.post(`/api/search/mapboundary?page=${this.page}`, this.data)
                .then(res => {
                    this.$emit('locationevents', res.data);
                });
            },

            onlinepage() {
                axios.post(`/api/search/online?page=${this.onlinepage}`, this.data)
                .then(res => {
                    this.$emit('onlineevents', res.data);
                });
            },

            '$store.state.filter': function(n) {
                console.log(n);
                return n ? this.active='mobile' : this.active='';
              }

        },

        created() {
            this.getPushState();
        },


}
    
</script>