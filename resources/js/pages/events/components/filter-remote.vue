<template>
    <div>
        <div v-if="mobile" class="event-search__filters grid">
            <div class="e-search-filter__row">
                <button @click="showFilters=true" class="filter">Filters</button>
            </div>
        </div>
        <div v-else class="e-search-filter__row grid">
            <!-- Category Search -->
            <div class="e-search-filter__item">
                <div class="e-search-filter__button" ref="cat">
                    <button @click="show('category')" :class="{ active:category }" class="filter">
                        <p v-if="!category">Categories</p>
                        <p v-if="category">{{category.name}}</p>
                    </button>
                    <div v-if="active === 'category'" class="e-search-filter__pop-box">
                        <div class="e-search-filter__pop-box--category">
                            
                        </div>
                        <div class="e-search-filter__pop-box--footer">
                            <button v-if="category" @click="clearCat()" class="pop-box__cancel">clear</button>
                            <button v-if="!category" @click="active = null;" class="pop-box__cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Location Search -->
            <div v-if="!category.remote || category == ''" class="e-search-filter__item">
                <div>
                    
                </div>
            </div>
            <!-- Date Search -->
            <div class="e-search-filter__item">
                <div class="e-search-filter__button" ref="dates">
                    <button @click="show('dates')" :class="{ active : datesFormatted.length }" class="filter">
                        <p v-if="!datesFormatted.length">Dates</p>
                        <p v-if="datesFormatted.length">{{datesFormatted[0]}}{{ datesFormatted[1] ? ' to ' + datesFormatted[1] : ''}} </p>
                    </button>
                    <div class="e-search-filter__pop-box" v-if="active === 'dates'">
                        <div>
                            <flat-pickr
                                v-model="dates"
                                :config="config"                                         
                                placeholder="Select date"               
                                name="dates">
                            </flat-pickr>
                        </div>
                        <div class="e-search-filter__pop-box--footer">
                            <button v-if="datesFormatted.length" @click="datesFormatted = []; datesSubmit = []; dates = [];" class="pop-box__cancel">clear</button>
                            <button v-if="!datesFormatted.length" @click="active = null" class="pop-box__cancel">Cancel</button>
                            <button class="pop-box__submit" @click="submitNew()">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Price Search -->
            <div class="e-search-filter__item">
                <div class="e-search-filter__button" ref="price">
                    <button @click="show('price')" :class="{ active : !showPrice }" class="filter">
                        <p v-if="!showPrice && price[0] == 0">{{' Up to ' + '$' + price[1]}}</p>
                        <p v-if="!showPrice && price[0] != 0">{{'$' + price[0]}}{{' to ' + '$' + price[1]}}</p>
                        <p v-if="showPrice">Price</p>
                    </button>
                    <div v-if="active === 'price'" class="e-search-filter__pop-box">
                        <div class="e-search-filter__pop-box--price">
                            <vue-slider
                            v-model="price" 
                            v-bind="options"
                            :enable-cross="false" />
                            </vue-slider>
                            <div class="price-box__amount">
                                <div class="price-box__amount--info">
                                    <label> Min </label>
                                    <input type="text"v-model="price[0]">
                                </div>
                                <div class="price-box__amount--info">
                                    <label> Max </label>
                                    <input type="text"v-model="price[1]">
                                </div>
                            </div>
                        </div>
                        <div class="e-search-filter__pop-box--footer">
                            <button v-if="showPrice" @click="active = null" class="pop-box__cancel">Cancel</button>
                            <button v-if="!showPrice" @click="price = [options.min, options.max]" class="pop-box__cancel">clear</button>
                            <button @click="submitNew" class="pop-box__submit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tag Search -->
            <div class="e-search-filter__item">
                <div class="e-search-filter__button" ref="tag">
                    <button @click="show('tag')" :class="{ active : tag }" class="filter">
                        <p v-if="!tag">Tags</p>
                        <p v-if="tag">{{tag.name}}</p>
                    </button>
                    <div v-if="active === 'tag'" class="e-search-filter__pop-box">
                        <div class="e-search-filter__pop-box--category">
                            
                        </div>
                        <div class="e-search-filter__pop-box--footer">
                            <button v-if="tag" @click="clearTag()" class="pop-box__cancel">clear</button>
                            <button v-if="!tag" @click="active = null;" class="pop-box__cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Mobile Filter List -->
        <div v-if="mobile" class="mobile-filter-list" v-show="showFilters">
            <div class="mobile-filter-list__nav">
                <button @click="cancelMobile" class="mobile-filter-list__nav--close-button">
                    <svg viewBox="0 0 12 12" role="presentation" aria-hidden="true" focusable="false" style="height: 14px; width: 14px; display: block; fill: currentcolor;"><path d="m11.5 10.5c.3.3.3.8 0 1.1s-.8.3-1.1 0l-4.4-4.5-4.5 4.5c-.3.3-.8.3-1.1 0s-.3-.8 0-1.1l4.5-4.5-4.4-4.5c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l4.4 4.5 4.5-4.5c.3-.3.8-.3 1.1 0s .3.8 0 1.1l-4.5 4.5z" fill-rule="evenodd"></path></svg>
                </button>
                <div class="mobile-filter-list__clear">
                    <button class="mobile-filter-list__clear-button" @click="clearMobile">Clear</button>
                </div>
            </div>
            <div class="mobile-filter-list__content">
                
                 <!-- Mobile Location Search -->
                <div v-if="!category.remote || category == ''" class="mobile-filter-list__content--categories">
                    <h3>Location</h3>
                   
                </div>
                 <!-- Mobile Date Search -->
                <div class="mobile-filter-list__content--dates">
                    <h3>Dates</h3>
                    <flat-pickr
                        v-model="dates"
                        :config="configmobile"                                         
                        placeholder="Select date"               
                        name="dates">
                    </flat-pickr>
                </div>

                 <!-- Mobile Category Search -->
                <div class="mobile-filter-list__content--categories">
                    <h3>Categories</h3>
                    
                </div>
                
                 <!-- Mobile Price Search -->
                <div class="mobile-filter-list__content--prices">
                    <h3>Prices</h3>
                    <div class="mobile-filter-list__price-box">
                        <vue-slider
                        v-model="price" 
                        v-bind="options"
                        :enable-cross="false" />
                        <div class="price-box__amount">
                            <div class="price-box__amount--info">
                                <label> Min </label>
                                <input type="text"v-model="price[0]">
                            </div>
                            <div class="price-box__amount--info">
                                <label> Max </label>
                                <input type="text"v-model="price[1]">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mobile-filter-list__footer">
                <div class="mobile-filter-list__footer--button">
                    <button class="mobile-filter-button" @click="submitMobile">Filter</button>
                </div>
            </div>
        </div>

        <section v-cloak>
            <div class="padded-width">
                <vue-event-index :events="eventList"></vue-event-index>
            </div>
        </section>
        <load-more @intersect="intersected"></load-more>
    </div>
</template>
<script>
    import flatPickr from 'vue-flatpickr-component'
    import 'flatpickr/dist/flatpickr.css'
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'
    import LoadMore  from '../../../components/LoadMore.js'
    import dayjs from 'dayjs'

    export default {

        props:['categories','maxprice', 'events', 'tags'],

        components: { flatPickr, VueSlider, LoadMore },

        computed: {
            showPrice() {
                return this.price[1] == this.options.max && this.price[0] == this.options.min ? true : false;
            },


            submitObject() {
                return {
                    category: this.category ? this.category.id : null,
                    tag: this.tag ? this.tag.name : null,
                    dates: this.datesSubmit.length ? this.datesSubmit : null,
                    price: !this.showPrice ? this.price : null,
                }
            },
        },

        data() {
            return {
                eventList: this.events,
                searchList:this.events,
                dates: [],
                datesSubmit: [],
                datesFormatted: [],
                config: this.initializeConfigObject(),
                configmobile: this.initializeConfigObject(),
                active: '',
                category: '',
                tag: '',
                price: [0, this.maxprice],
                hasPrice: false,
                options: { min: 0, max: this.maxprice },
                showFilters: false,
                mobile: window.innerWidth < 768,
                searchcategory: new URL(window.location.href).searchParams.get("category"),
                searchtag: new URL(window.location.href).searchParams.get("tag"),
                searchdates: new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : '',
                searchremote: new URL(window.location.href).searchParams.get("remote"),
                id: new URL(window.location.href).searchParams.get("id"),
                searchBoxInput: [],
                searchBoxOptions: [ {name: 'Loading List...'}],
                page: 2,
                pagination:'',
                hasFilter: false,
                loadMore: true,
            }
        },

        methods: {
            initializeConfigObject(){
                return {
                    // minDate: "today",
                    altFormat:'M d',
                    altInput: true,
                    mode: "range",
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    onClose: [this.dateFunc()], 
                }
            },

            onSubmit() {
                this.active = null;
                this.loadMore = false;
                axios.post(`/api/search/remote?page=${this.page}`, this.submitObject)
                .then(res => {
                    res.data.current_page == 1 ? this.eventList = res.data.data : this.eventList = this.eventList.concat(res.data.data);
                    this.pagination = res.data;
                    this.page = res.data.current_page + 1;
                    this.loadMore = true;
                })
                .catch(errorResponse => { 
                   console.log(errorResponse.data);
                });
            },

            intersected() {
                // console.log(this.loadMore);
                // console.log(this.pagination);
                if (!this.loadMore) {return false;}
                if (this.pagination.last_page < this.page ) {return false};
                this.onSubmit();
            },

            show(type) {
                this.active === type ? this.active = null : this.active = type;
                setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
            },

            submitNew() {
                this.hasFilter = true;
                this.page = 1;
                this.eventList = [];
                this.onSubmit();
            },

            submitCategory(value) {
                this.$store.commit('searchtype', value.name)
                this.category = value;
                this.submitNew();
            },

            submitTag(value) {
                this.$store.commit('searchtype', value.name)
                this.tag = value;
                this.submitNew();
            },

            submitMobile() {
                this.submitNew();
                this.showFilters = false;
            },

            clearCat() {
                this.category = '';
                this.page = 1;
                this.eventList = [];
                this.$store.commit('searchtype', 'Immersive Theater')
                this.onSubmit();
            },

            clearTag() {
                this.tag = '';
                this.page = 1;
                this.eventList = [];
                this.category ? this.$store.commit('searchtype', this.category.name) : this.$store.commit('searchtype', 'Immersive Theater')
                this.onSubmit();
            },

            cancelMobile() {
                this.clearMobile();
                this.showFilters = false;
            },

            clearMobile() {
                this.price = [this.options.min, this.options.max]
                this.datesFormatted = [];
                this.datesSubmit = [];
                this.dates = [];
                this.category = '';
                this.submitNew();
            },

            dateFunc() {
            // Save component this in that
            const that = this;
            // return function needed
            return function(value) {
                that.datesSubmit = value.map(date => 
                    this.formatDate(date, "Y-m-d H:i:S"));
                that.datesFormatted = value.map(date => 
                    this.formatDate(date, "M d"));
                }
            },

            onClickOutside(event) {
                if (this.active == null) {return false};
                let cat =  this.$refs.cat;
                let tag =  this.$refs.tag;
                let dates =  this.$refs.dates;
                let price =  this.$refs.price;
                if (!cat || cat.contains(event.target) || !tag || tag.contains(event.target) || !dates || dates.contains(event.target) || !price || price.contains(event.target)) return;
                this.active = null;
                this.submitNew();
            },

            onLoad() {
                if (this.searchcategory) {
                    this.category = this.categories.find(element => element.id == this.id);
                    this.$store.commit('searchtype', this.category.name)
                    this.submitNew();
                }
                if (this.searchtag) {
                    this.tag = this.tags.find(element => element.id == this.id);
                    this.$store.commit('searchtype', this.tag.name)
                    this.submitNew();
                }
                if (this.searchdates) {
                    this.dates = this.searchdates;
                    this.datesSubmit = this.searchdates.map(date => dayjs(date).format("YYYY-MM-DD HH:mm:ss"));
                    this.datesFormatted = this.searchdates.map(date => dayjs(date).format("MMM D"));
                    this.submitNew();
                }
            },

            asyncGenerateCitiesList (query) {
                this.active = null;
                axios.get('/api/search/location', { params: { keywords: query } })
                .then(res => {
                    console.log(res.data);
                    this.searchBoxOptions = res.data.data;
                });
            },

            searchLocation() {
                let cat = this.category ? this.category.id : '';
                window.location.href = `/index/search?name=${this.searchBoxInput.name}&lat=${this.searchBoxInput.latitude}&lng=${this.searchBoxInput.longitude}&category=${cat}`;
            },

            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                    el.classList.add(className);
                } else {
                    el.classList.remove(className);
                }
            },
        },

        watch: {
            showFilters() {
                return this.showFilters ? this.toggleBodyClass('addClass', 'noscroll') : this.toggleBodyClass('removeClass', 'noscroll');
            },
        },

        created() {
            this.onLoad();
        },


}
</script>