<template>
    <div class="show-content">
        <nav class="event-show mobile">
            <div class="back">
                <a @click="goBack()">
                    <div class="event-show-nav__back-arrow">
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
                </a>
            </div>
            <div class="event-show-nav__favorite">
                <favorite 
                    :inputclass="showEventMobileClass" 
                    :event="event" />
            </div>
        </nav>

        <!-- header -->
        <header class="event-show grid">
            <div class="header-left">
                <div class="content">   
                    <a 
                        v-if="event.category" 
                        :href="`/index/search-online?category=${event.category.id}`">
                        <span class="header-left__cat"> {{ event.category.name }} </span>
                    </a>
                    <span class="header-left__title"><h1 :style="titleFontSize"> {{ event.name }} </h1></span>
                    <span class="header-left__tag"><i> {{ event.tag_line }} </i></span>
                    <div class="header-left__staffpick">
                        <template v-if="event.staffpick">
                            Staff Pick
                        </template>
                    </div>
                    <div class="header-left__details">
                        <a href="#tickets">
                            <div class="item">
                                <img src="/storage/website-files/price.png">
                                <span>
                                    <span class="header__show-info">
                                        Price
                                    </span>
                                    <span class="header__show-info bold">
                                        {{ event.price_range }}
                                    </span>
                                </span>
                            </div>
                        </a>
                        <a href="#location">
                            <div class="item">
                                <img src="/storage/website-files/location.png">
                                <span v-if="event.hasLocation">
                                    <span class="header__show-info">Location</span>
                                    <span class="header__show-info bold"><span v-if="event.location.city"> {{ event.location.city }},  </span><span v-if="event.location.region"> {{ event.location.region }} </span></span>
                                </span>
                                <span v-else>
                                    <span class="header__show-info">Location</span>
                                    <span class="header__show-info bold">Anywhere</span>
                                </span>
                            </div>
                        </a>
                        <a href="#dates">
                            <div class="item">
                                <img src="/storage/website-files/calendar.png">
                                <span v-if="event.showtype=='s'">
                                    <span class="header__show-info">Shows</span>
                                    <span 
                                        v-if="remaining && remaining.length > 1 ? remaining.length : ''" 
                                        class="header__show-info bold">{{ remaining.length }} dates left</span>
                                    <span 
                                        v-else-if="remaining && remaining.length == 1 ? remaining.length : ''" 
                                        class="header__show-info bold">{{ remaining.length }} date left</span>
                                    <span 
                                        v-else 
                                        class="header__show-info bold">no dates left</span>
                                </span>
                                <span v-if="event.showtype=='o' || event.showtype=='l'">
                                    <span class="header__show-info">Shows</span>
                                    <span class="header__show-info bold">
                                        <span v-if="event.show_on_going.mon">M</span>
                                        <span v-else style="color:#bbbbbb;">M</span>
                                        <span v-if="event.show_on_going.tue">T</span>
                                        <span v-else style="color:#bbbbbb;">T</span>
                                        <span v-if="event.show_on_going.wed">W</span>
                                        <span v-else style="color:#bbbbbb;">W</span>
                                        <span v-if="event.show_on_going.thu">T</span>
                                        <span v-else style="color:#bbbbbb;">T</span>
                                        <span v-if="event.show_on_going.fri">F</span>
                                        <span v-else style="color:#bbbbbb;">F</span>
                                        <span v-if="event.show_on_going.sat">S</span>
                                        <span v-else style="color:#bbbbbb;">S</span>
                                        <span v-if="event.show_on_going.sun">S</span>
                                        <span v-else style="color:#bbbbbb;">S</span>
                                    </span>
                                </span>
                                <span v-if="event.showtype=='a'">
                                    <span class="header__show-info">Show Days</span>
                                    <span class="header__show-info bold">Any Time</span>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="header-right__image">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${isMobile ? event.thumbImagePath : event.largeImagePath}`"> 
                        <img 
                            :src="`/storage/${isMobile ? event.thumbImagePath.slice(0, -4) : event.largeImagePath.slice(0, -4)}jpg`" 
                            :alt="`${event.name} Immersive Event`">
                    </picture>
                </div>
                <div class="desktop">
                    <div 
                        v-if="user && user.type == 'a'" 
                        class="es__admin--edit">
                        <a :href="`/create/${event.slug}/title`">
                            <p>Edit</p>
                        </a>
                    </div>
                    <favorite 
                        :inputclass="showEventClass" 
                        :event="event" />
                </div>
            </div>
        </header>
       
        <!-- About -->
        <section class="event-show grid two-panel">
            <div class="event-title">
                <h2>About</h2>
            </div>
            <div class="right">
                <div class="event-show__description">
                    <ShowMore 
                        :text="event.description"
                        :limit="100" />
                </div>
            </div>
        </section>

        <!-- StaffPick -->
        <template v-if="event.staffpick">
            <section class="event-show grid two-panel">
                <div class="event-title">
                    <h2>Staff Pick</h2>
                </div>
                <div class="right">
                    <div class="event-show__description">
                        <ShowMore 
                            :text="event.staffpick.comments"
                            :limit="100" />
                    </div>
                    <div class="event-show__staffpicks__user">
                        <div class="name">
                            <i><p>-{{ event.staffpick.user.name }}</p></i>
                        </div>
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${event.staffpick.user.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${event.staffpick.user.thumbImagePath.slice(0, -4)}jpg`" 
                                :alt="`${event.staffpick.user.name} Immersive Event`">
                        </picture>
                    </div>
                </div>
            </section>
        </template>

        <!-- Tickets -->
        <section 
            id="tickets" 
            class="event-show grid two-panel grey">
            <div class="event-title">
                <h2>Tickets</h2>
            </div>
            <div class="right">
                <div class="event-show__tickets">
                    <div class="event-show__tickets--grid">
                        <a 
                            :href="eventUrl" 
                            rel="noreferrer noopener" 
                            target="_blank" 
                            v-for="ticket in tickets" 
                            :key="ticket.name">
                            <div 
                                class="event-show__ticket" 
                                @mouseover="hover = ticket" 
                                @mouseleave="hover = null">
                                <div>
                                    <div class="event-show__ticket--name"><p> {{ ticket.name }} </p></div>
                                    <div 
                                        v-if="ticket.type == 'f'" 
                                        class="event-show__ticket--price">
                                        <p>Free</p>
                                    </div>
                                    <div 
                                        v-else-if="ticket.type == 'p'" 
                                        class="event-show__ticket--price">
                                        <p>Pay what you can</p>
                                    </div>
                                    <div 
                                        v-else 
                                        class="event-show__ticket--price">
                                        <p> {{ ticket.ticket_price == 0.00 ? 'Free' : `${ticket.currency} ${ticket.ticket_price}` }} </p>
                                    </div>
                                </div>
                                <div class="event-show__ticket--description">
                                    <p> {{ ticket.description }} </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dates -->
        <section 
            id="dates" 
            v-if="event.showtype == 's'" 
            class="grid event-show two-panel">
            <div class="event-title">
                <h2>Show Dates</h2>
            </div>
            <div class="right lockedcalendar desktop">
                <flat-pickr
                    v-model="dates"
                    :config="config"                                                  
                    class="form-control"
                    placeholder="Select date"
                    ref="datePicker"             
                    name="dates" />
                <div class="event-show__showtimes--specific">
                    <div class="event-show__dates--details">
                        <h3>Show Details</h3>
                    </div>
                    <p> {{ event.timezone ? event.timezone.description : '' }} </p>
                    <ShowMore 
                        :text="event.show_times"
                        :limit="20" />
                </div>
            </div>
            <div class="right lockedcalendar mobile">
                <flat-pickr
                    v-model="dates"
                    :config="config"                                  
                    class="form-control"
                    placeholder="Select date"
                    ref="datePicker"             
                    name="dates" />
                <div class="event-show__showtimes--specific">
                    <div class="event-show__dates--details">
                        <h3>Show Details</h3>
                    </div>
                    <p> {{ event.timezone ? event.timezone.description : '' }} </p>
                    <ShowMore 
                        :text="event.show_times"
                        :limit="20" />
                </div>
            </div>
        </section>

        <!-- Dates On Going -->
        <section 
            id="dates" 
            v-if="event.showtype == 'o'" 
            class="grid event-show two-panel">
            <div class="event-title">
                <h2>Show Times</h2>
            </div>
            <div class="right">
                <div class="calendar">
                    <div class="field">
                        <div class="grid show-week-calendar">
                            <div 
                                class="show-week-calendar_day" 
                                :class="{ active: week.mon }">
                                <h4>Mon</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day"
                                :class="{ active: week.tue }">
                                <h4>Tue</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day" 
                                :class="{ active: week.wed }">
                                <h4>Wed</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day" 
                                :class="{ active: week.thu }">
                                <h4>Thu</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day"
                                :class="{ active: week.fri }">
                                <h4>Fri</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day"
                                :class="{ active: week.sat }">
                                <h4>Sat</h4>
                            </div>
                            <div 
                                class="show-week-calendar_day"
                                :class="{ active: week.sun }">
                                <h4>Sun</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="event-show__showtimes--ongoing">
                    <div class="event-show__dates--details">
                        <h3>Show Details</h3>
                    </div>
                    <p> {{ event.timezone ? event.timezone.description : '' }} </p>
                    <ShowMore 
                        :text="event.show_times"
                        :limit="20" />
                </div>
            </div>
        </section>

        <!-- Dates Always -->
        <section 
            id="dates" 
            v-if="event.showtype == 'a'" 
            class="grid event-show two-panel">
            <div class="event-title">
                <h2>Show Times</h2>
            </div>
            <div class="right">
                <h3>Anytime</h3>
                <div class="event-show__showtimes--specific">
                    <div class="event-show__dates--details">
                        <h3>Show Details</h3>
                    </div>
                    <p> {{ event.timezone ? event.timezone.description : '' }} </p>
                    <ShowMore 
                        :text="event.show_times"
                        :limit="20" />
                </div>
            </div>
        </section>

        <!-- Details -->
        <section class="grid event-show two-panel">
            <div class="left">
                <div class="event-title">
                    <h2>Show Details</h2>
                </div>
            </div>
            <div class="right">
                <div class=" grid two-panel">
                    <div class="title">
                        <h3>Content Advisories</h3>
                    </div>
                    <ul class="info">
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
                <div class="grid two-panel">
                    <div class="title">
                        <h3>Interaction Advisories</h3>
                    </div>
                    <ul class="info">
                        <li 
                            v-for="item in event.contact_levels"
                            :key="item.id">
                            <p> {{ item.level }} </p>
                        </li>
                    </ul>
                </div>
                <div class="grid two-panel">
                    <div class="title">
                        <h3>Mobility Advisories</h3>
                    </div>
                    <ul class="info">
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
                <div class="grid two-panel">
                    <div class="title">
                        <h3>Audience Role</h3>
                    </div>
                    <ul class="audience-role__info">
                        <li>
                            <ShowMore 
                                :text="event.advisories.audience"
                                :limit="30" />
                        </li>
                    </ul>
                </div>
                <div class="grid two-panel">
                    <div class="title">
                        <h3>Tags</h3>
                    </div>
                    <ul class="info">
                        <div 
                            v-for="item in event.genres"
                            :key="item.id">
                            <a 
                                v-if="item.admin == 1" 
                                :href="`/index/search-online?tag=${item.name}&id=${item.id}`"><b> {{ item.name }} </b></a>
                            <p v-else> {{ item.name }} </p>
                        </div>
                    </ul>
                </div>
                <div 
                    class="grid two-panel" 
                    v-if="event.advisories.sexual">
                    <div class="title">
                        <h3>Sexual Advisories</h3>
                    </div>
                    <ul class="info">
                        <li>
                            <p> {{ event.advisories.sexualDescription }} </p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Reviews -->
        <section 
            v-if="event.eventreviews ? event.eventreviews.length : null" 
            class=" event-show grey event-show-review">
            <div class="content grid two-panel">
                <div class="left">
                    <div class="event-title">
                        <h2>Show Reviews</h2>
                    </div>
                </div>
                <div class="event-show-review__right">
                    <div 
                        class="box" 
                        v-for="review in event.eventreviews"
                        :key="review.id">
                        <a 
                            rel="noreferrer" 
                            target="_blank" 
                            :href="review.url">
                            <div class="event-show-review__right--image">
                                <img 
                                    width="33px" 
                                    height="33px" 
                                    :src="review.image_path">
                            </div>
                            <div class="event-show-review__right--name">
                                <h4> {{ review.reviewer_name }}'s Review</h4>
                            </div>
                        </a>
                        <div class="review">
                            <a 
                                rel="noreferrer" 
                                target="_blank" 
                                :href="review.url">                 
                                <i 
                                    style="white-space: pre-wrap;"
                                    class="text"> {{ review.review.substring(0,300) }}...<span class="show-text"> Read More
                                    </span>
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Organizer -->
        <section class="grid event-show two-panel">
            <div class="left">
                <div class="event-title">
                    <h2>About the organizer</h2>
                </div>
            </div>
            <div class="right">
                <a :href="`/organizer/${event.organizer.slug}`">
                    <div class="event-show__organizer--image">
                        <div 
                            :style="event.organizer.thumbImagePath ? organizerImage : `background:${event.organizer.hexColor}`" 
                            class="img">
                            <div 
                                class="organizer-icon-text event-show" 
                                v-if="!event.organizer.thumbImagePath">
                                <span> {{ event.organizer.name.charAt(0) }} </span>
                            </div>
                        </div>
                    </div>
                </a>
                <a :href="`/organizer/${event.organizer.slug}`">
                    <div class="event-show__organizer--name">
                        <h3> {{ event.organizer.name }} </h3>
                    </div>
                </a>
                <ShowMore 
                    :text="event.organizer.description"
                    :limit="50" />
            </div>
        </section>

        <!-- location -->
        <section 
            id="location" 
            v-if="event.hasLocation" 
            class="section event-show location">
            <div>
                <div class="event-title">
                    <h2>Location</h2>
                </div>
                <div 
                    class="text" 
                    v-if="event.location.hiddenLocationToggle">
                    <a 
                        rel="noreferrer" 
                        target="_blank" 
                        :href="`http://maps.google.com/maps?q=${event.location.city},+${event.location.region}`">
                        <b><p v-if="event.location.venue"> {{ event.location.venue }} </p></b>
                        <p><span v-if="event.location.city"> {{ event.location.city }},</span> <span v-if="event.location.region"> {{ event.location.region }} </span></p>
                        <br>
                        <p> {{ event.location.hiddenLocation }} </p>
                    </a>
                </div>
                <div 
                    class="text" 
                    v-else>
                    <a 
                        rel="noreferrer" 
                        target="_blank" 
                        :href="`http://maps.google.com/maps?q=${event.location.home}+${event.location.street},+${event.location.city},+${event.location.region}`">
                        <b><p v-if="event.location.venue"> {{ event.location.venue }} </p></b>
                        <p>
                            <span v-if="event.location.home"> {{ event.location.home }} </span> 
                            <span v-if="event.location.street"> {{ event.location.street }} </span> 
                        </p>
                        <p>
                            <span v-if="event.location.city"> {{ event.location.city }}</span> 
                            <span v-if="event.location.region">{{ event.location.region }}</span>
                            <span v-if="event.location.country">{{ event.location.country }}</span>
                            <span v-if="event.location.postal_code">{{ event.location.postal_code }}</span>
                        </p>  
                    </a>
                </div>
                <div class="event-show-map">
                    <div v-if="center">
                        <div style="width:100%;height:400px">
                            <l-map 
                                :zoom="zoom" 
                                :center="center" 
                                :options="{ scrollWheelZoom: false, zoomControl: true }">
                                <l-tile-layer :url="url" />
                                <l-marker :lat-lng="center" />
                            </l-map>
                        </div>  
                    </div>
                </div>
            </div>
        </section>

        <!-- No Location -->
        <section 
            id="location" 
            class="event-show grid two-panel" 
            v-else>
            <div class="event-title">
                <h2>What you will need</h2>
            </div>
            <div class="right">
                <div class="event-show__remote--area">
                    <div 
                        class="event-show__remote--type" 
                        v-for="location in event.remotelocations"
                        :key="location.id">
                        <h4 class="event-show__remote--name">
                            {{ location.name }}
                        </h4>
                        <p class="event-show__remote--description">
                            {{ location.description }}
                        </p>
                    </div>
                </div>
                <div 
                    class="event-show__remote--userinfo" 
                    style="white-space: pre-wrap;" 
                    v-if="event.remote_description">
                    <h4>Additional Instructions</h4>
                    <p>{{ event.remote_description }}</p>
                </div>
            </div>
        </section>

        <div 
            class="grid event-bottom-bar" 
            :class="{ active: bar }">
            <div class="event-bottom-bar__name">
                <picture>
                    <source 
                        height="50" 
                        width="50" 
                        type="image/webp" 
                        :srcset="`/storage/${event.thumbImagePath}`"> 
                    <img 
                        class="event-bottom-bar__img" 
                        height="50" 
                        width="50" 
                        :src="`/storage/${event.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="event.name">
                </picture>
                <h4>{{ event.name }}</h4>
            </div>
            <div class="event-price">
                <h4>{{ event.price_range }}</h4>
            </div>
            <div class="event-get-tickets">
                <a 
                    :href="eventUrl" 
                    rel="noreferrer noopener" 
                    target="_blank">
                    <button class="event-bottom-bar__button">
                        {{ loadevent.call_to_action ? loadevent.call_to_action : 'Get Tickets' }}
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet'
    import flatPickr from 'vue-flatpickr-component'
    import ShowMore  from './components/show-more.vue'
    export default {
        props: ['loadevent', 'user', 'tickets'],
        components: { LMap, LTileLayer, LMarker, flatPickr, LPopup, ShowMore},
        computed: {
            locationPlaceholder() {
                return this.event.location.postal_code || this.event.location.city ? (this.event.location.home ? this.event.location.home + ' ' : '') 
                + (this.event.location.street ? this.event.location.street + ' | ' : '') 
                + (this.event.location.city ? this.event.location.city + ' | ' : '') 
                + (this.event.location.region ? this.event.location.region + ' | ' : '') 
                + (this.event.location.country ? this.event.location.country : '') 
                : '';
            },
            eventUrl() {
                if (this.loadevent.ticketUrl) {return this.loadevent.ticketUrl}
                if (this.loadevent.websiteUrl) {return this.loadevent.websiteUrl}
                return this.loadevent.organizer.website;
            },
        },
        data() {
            return {
                event: this.loadevent ? this.loadevent : '',
                zoom:13,
                center: this.loadevent.location_latlon,
                url:'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                week: this.loadevent ? this.loadevent.show_on_going : '',
                showEventClass: 'es__favorite',
                showEventMobileClass: 'es__favorite--mobile',
                organizerImage: '',
                dates: [],
                remaining: [],
                bar: false,
                lastScrollPosition: 0,
                config: this.initializeCalendarObject(),
                titleFontSize: '',
                hover: null,
                isMobile: window.innerWidth < 768 ? true : false,
            }
        },
        methods: {
            getDates() {
                if(this.event.shows) {
                    this.event.shows.forEach(event=> {
                        if (this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < event.date) {
                            this.remaining.push(event.date);
                        } else {
                            this.config.disable.push(event.date);
                        }
                        this.dates.push(event.date);
                    });
                }
            },
            handleScroll () {
                const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
                return currentScrollPosition > 60 ? this.bar = true : this.bar = false
            },
            canUseWebP() {
                let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
                if (this.loadevent.organizer.thumbImagePath && webp) {
                    return this.organizerImage = `background-image:url('/storage/${this.loadevent.organizer.thumbImagePath}')`;
                }
                if (this.loadevent.organizer.thumbImagePath) {
                    return this.organizerImage = `background-image:url('/storage/${this.loadevent.organizer.thumbImagePath.slice(0, -4)}jpg')`;
                }
            },
            initializeCalendarObject() { 
                return {
                    maxDate: new Date().fp_incr(180),
                    mode: "multiple",
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    disable: [],
                }
            },
            
            getTitleFontSize() {
                if (this.event.name.length > 70) { return this.titleFontSize = `font-size:3.2rem;line-height:3rem` }
                if (this.event.name.length > 40) { return this.titleFontSize = `font-size:3.5rem;line-height:4rem` }
                return this.titleFontSize = `font-size:4rem;line-height:5rem`
            },
            goBack() {
                window.history.back();
            }
        },
        mounted() {
            this.getDates();
            this.canUseWebP();
            this.getTitleFontSize();
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        unmounted () {
            window.removeEventListener('scroll', this.handleScroll);
        },
    };
</script>