<template>
    <div class="event-create__shows">
        <section class="">
            <div class="listing-details-block">
                <div class="title">
                    <h2>Pricing & Access</h2>
                </div>
                <section 
                    v-if="event.showtype" 
                    class="event-enter-tickets">
                    <div class="field cost">
                        <div class="event-tickets__add">
                            <div class="field">
                                <label class="area">Let our users know your ticket pricing (if no cost choose free)</label>
                                <v-select 
                                    v-model="selected"
                                    label="name"
                                    taggable
                                    :options="ticketOptionslive"
                                    :clearable="false"
                                    placeholder="Ticket class (type here to create your own. Max 20 chars)"
                                    :create-option="ticket => ({ name: ticket, type: this.initializeTypeObject() })"
                                    @search:blur="active = null"
                                    @search:focus="active = 'newTicket'"
                                    @input="pushTicket"
                                    :class="{ active: active == 'newTicket', 'error': $v.tickets.$error}" />
                                <div v-if="$v.tickets.$error" class="validation-error">
                                    <p class="error" v-if="!$v.tickets.required">
                                        Enter at least one ticket type (Select or Type)
                                    </p>
                                </div>
                            </div>
                        </div>
                        <template v-if="tickets.length">
                            <div class="create-shows__ticket-box">
                                <div class="ticket-box__element labels grid">
                                    <div>
                                        <label>Ticket Class</label>
                                    </div>
                                    <div>
                                        <label>Description (include shipping fees)</label>
                                    </div>
                                    <div>
                                        <label>Type (free, etc...)</label>
                                    </div>
                                    <div />
                                    <div>
                                        <label>Ticket Price</label>
                                    </div>
                                </div>
    
                                <div 
                                    v-for="(ticket, index) in tickets" 
                                    :key="ticket.id"
                                    class="ticket-box__element grid">
                                    <div class="field">
                                        <input 
                                            v-model="ticket.name" 
                                            class="create-input"  
                                            name="name"
                                            @input="$v.selected.$touch"
                                            :class="{ active: active == `name${index}`, error: ticket.errors.nameLength || ticket.errors.nameEmpty || ticket.errors.nameRepeat}"
                                            @click="active = `name${index}`"
                                            @keydown="reset"
                                            @blur="active = null"
                                            placeholder="ex: General, VIP, Student">
                                        <div 
                                            v-if="ticket.errors.nameLength || ticket.errors.nameRepeat || ticket.errors.nameEmpty" 
                                            class="validation-error">
                                            <p v-if="ticket.errors.nameLength" class="error">Name is too long</p>
                                            <p v-if="ticket.errors.nameRepeat" class="error">No duplicate names</p>
                                            <p v-if="ticket.errors.nameEmpty" class="error">Name is required</p>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <input 
                                            v-model="ticket.description"
                                            @input="reset"
                                            @click="active = `description${index}`"
                                            :class="{ active: active == `description${index}`, error: ticket.errors.descriptionLength}"
                                            @blur="active = null"
                                            placeholder="Per person, home, team, car, etc.">
                                        <div v-if="ticket.errors.descriptionLength"  class="validation-error">
                                            <p v-if="ticket.errors.descriptionLength" class="error">Description is too long</p>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <v-select 
                                            v-model="ticket.type" 
                                            label="name"
                                            :options="ticketTypeOptions"
                                            :clearable="false"
                                            @search:blur="active = null"
                                            @search:focus="active = 'type'"
                                            @input="$v.selected.$touch"
                                            :class="{ active: active == 'type' }" />
                                    </div>
                                    <div v-if="ticket.type.type == 'f' || ticket.type.type == 'p'" />
                                    <template v-else>
                                        <div class="field">
                                            <select 
                                                v-model="currency" 
                                                @change="$v.selected.$touch"
                                                placeholder="Leave blank for default Rank of 5 (1 being most important)">
                                                <option 
                                                    v-for="option in ticketCurrencyOptions"
                                                    :key="option">
                                                    {{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </template>
                                    <div 
                                        v-if="ticket.type.type == 'f'" 
                                        class="field">
                                        <div class="free-ticket__field">
                                            Free
                                        </div>
                                    </div>
                                    <div 
                                        v-else-if="ticket.type.type == 'p'" 
                                        class="field">
                                        <div class="free-ticket__field">
                                            PWYC
                                        </div>
                                    </div>
                                    <div 
                                        v-else 
                                        class="field">
                                        <input 
                                            v-model="ticket.ticket_price"
                                            v-money="money"
                                            :class="{ active: active == `price${index}`, error: ticket.errors.priceLength || ticket.errors.priceEmpty }"
                                            @click="active = `price${index}`"
                                            @blur="active = null"
                                            @keyup="reset"
                                            placeholder="$0.00"
                                            v-bind="money"
                                            @keydown="$event.key === '-' ? $event.preventDefault() : null"
                                            style="text-align: right">
                                        <div 
                                            v-if="ticket.errors.priceLength || ticket.errors.priceEmpty" 
                                            class="validation-error">
                                            <p v-if="ticket.errors.priceEmpty" class="error">Can't be zero</p>
                                            <p v-if="ticket.errors.priceLength" class="error">Price is too big</p>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <button 
                                            @click.prevent="deleteRow(index, ticket)" 
                                            class="delete-circle">
                                            X
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="c-ticklet-button">
                            <div class="field">
                                <label>Choose your call to action button text (your link for tickets or ticket info)</label>
                                <v-select 
                                    v-model="callAction" 
                                    placeholder="Select call to action"
                                    :options="callActionOptions"
                                    :clearable="false"
                                    @search:blur="active = null"
                                    @search:focus="active = 'call'"
                                    @input="$v.callAction.$touch"
                                    :class="{ active: active == 'call' }" />
                                <div v-if="$v.callAction.$error" class="validation-error">
                                    <p class="error" v-if="!$v.callAction.required">Must have call to action</p>
                                </div>
                            </div>
                            <div class="field">
                                <label>Call to action button link (required)</label>
                                <input 
                                    type="text" 
                                    v-model="ticketUrl"
                                    :class="{ active: active == 'ticket','error': $v.ticketUrl.$error }"
                                    @click="checkStatus"
                                    @input="$v.ticketUrl.$touch"
                                    @blur="active = null"
                                    placeholder="">
                                <div v-if="$v.ticketUrl.$error" class="validation-error">
                                    <p class="error" v-if="!$v.ticketUrl.url"> Must be a url (https://...)</p>
                                    <p class="error" v-if="!$v.ticketUrl.required">Please enter a URL</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section v-else>
                    Please head to Dates and Times to enter show times before adding tickets.
                </section>
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="shows"
            next="description" 
            :event="event" />
        <modal 
            v-if="modal" 
            @close="modal = false">
            <div slot="header">
                <div class="circle del">
                    <p>?</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Changing the event URL?</h3>
                <p>Editing the event url will require the event to be reapproved.</p>
            </div>
            <div slot="footer">
                <button 
                    class="btn del" 
                    @click="onResubmit()">
                    Change
                </button>
            </div>
        </modal>
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import _ from 'lodash'
    import { required, url } from 'vuelidate/lib/validators'
    import { Money } from 'v-money'
    import Submit  from './components/submit-buttons.vue'


export default {


    props: ['event'],

    mixins: [formValidationMixin],

    components: { Money, Submit},

    computed: {
        endpoint() {
            return `/create/${this.event.slug}/tickets`
        },
        
        submitObject() {
            return {
                'tickets': this.tickets,
                'ticketUrl': this.ticketUrl,
                'callAction': this.callAction,
                'resubmit': this.resubmit,
            }
        },
    },

    data() {
        return {
            tickets: [],
            money: this.initializeMoneyObject(),
            ticketOptions: [
                { name: 'General', ticket_price: '', description: '', type:this.initializeTypeObject()},
                { name: 'Student', ticket_price: '', description: '', type:this.initializeTypeObject()},
                { name: 'Senior', ticket_price: '', description: '', type:this.initializeTypeObject()},
                { name: 'VIP', ticket_price: '', description: '', type:this.initializeTypeObject()},
                { name: 'Free', ticket_price: '', description: '', type: {name: 'free',type:'f'}},
                { name: 'PWYC', ticket_price: '', description: '', type: {name: 'pwyc',type:'p'}},
            ],
            ticketTypeOptions: [
                { name: 'normal', type:'s'},
                { name: 'free', type:'f'},
                { name: 'pwyc', type:'p'},
            ],
            currency: '$',
            ticketCurrencyOptions: ['$', '£' ,'AU', 'R$', 'C$', '¥', '€', '₹', 'Rp','₽', '₩'],
            callAction: this.event.call_to_action,
            callActionOptions: ['Get Tickets', 'Sign Up', 'Download','Details'],
            disabled: false,
            active: null,
            modal: false,
            freeTicket: false,
            exit: false,
            selected: '',
            tempErrorName: false,
            temperrors: false,
            ticketOptionslive: [],
            ticketUrl: this.event.ticketUrl ? this.event.ticketUrl : '',
            updated: false,
            approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
            resubmit: false,
            creationPage: 5,
        }
    },

    methods: {

        deleteRow(index) {
            this.$delete(this.tickets, index)
            this.$v.selected.$touch();
        },

        pushTicket(value) {
            const val = {
                name: value.name,
                description: '',
                ticket_price:0.00,
                type: value.type,
                errors: this.initializeErrorObject(),
                currency: this.currency
            }
            this.tickets.push(val);
            this.selected = '';
            this.$v.selected.$touch();
        },

        reset() {
            _.forEach(this.tickets, (ticket) => {ticket.errors = {priceLength: false,priceEmpty: false,nameLength: false, nameEmpty: false, nameRepeat: false, descriptionLength:false};
            })
            this.$v.selected.$touch();
        },

        onLoad() {
            this.checkTickets();
            axios.get(this.onFetch('tickets'))
            .then(res => {
                this.tickets=[];
                for (var i = 0; i < res.data.tickets[0].tickets.length; i++) {
                    let tic = res.data.tickets[0].tickets[i];
                    this.currency = tic.currency;
                    this.tickets.push({
                        name: tic.name,
                        description: tic.description,
                        ticket_price: tic.ticket_price,
                        currency: tic.currency,
                        type: _.find(this.ticketTypeOptions, { 'type': tic.type }),
                        errors: this.initializeErrorObject()
                    })
                }
                if (!this.tickets.length) {
                    this.pushTicket({ name: 'General', ticket_price: '', description: '', type:this.initializeTypeObject()})
                }
                res.data.ticketUrl ? this.ticketUrl = res.data.ticketUrl : '';
            });
        },

        async onSubmit(value) {
            if ( this.checkForChanges(value) ) { return this.onForward(value) }
            if ( !this.customCheck() ) { return }
            if ( this.checkVuelidate() ) { return  }
            await axios.post(this.endpoint, this.submitObject)
            value == 'save' ? this.save() : this.onForward(value);
        },

        checkTickets() {
            this.ticketOptionslive = [];
            let selected = this.tickets.map(a => a.name);
            for (var i = 0; i < this.ticketOptions.length; i++) {
                let name = this.ticketOptions[i].name;
                selected.includes(name) ? '' : this.ticketOptionslive.push(this.ticketOptions[i]);
            }
        },

        onResubmit() {
            this.resubmit = true;
            this.approved = false;
            this.modal = false;
        },

        checkStatus() {
            this.active = 'ticket'
            if ((this.event.status == 'p' || this.event.status == 'e') && this.resubmit == false) this.modal = true 
        },

        customCheck() {
            let previous = [];
            for (var i = 0; i < this.tickets.length; i++) {
                let tic = this.tickets[i];
                tic.currency = this.currency;
                if (tic.ticket_price == 0.00 && tic.type.type == 's') {
                    tic.errors.priceEmpty = true;
                        return false;
                }
                if (tic.ticket_price.length > 7 && tic.type.type == 's') {
                    tic.errors.priceLength = true;
                        return false;
                }
                if (tic.type.type == 'f' || tic.type.type == 'p') {
                    tic.ticket_price = 0.00;
                }
                if (tic.name.length > 20) {
                    tic.errors.nameLength = true;
                        return false;
                }
                if (tic.name.length == 0) {
                    tic.errors.nameEmpty = true;
                        return false;
                }
                if (tic.description && tic.description.length > 50) {
                    tic.errors.descriptionLength = true;
                        return false;
                }
                if (previous.includes(this.tickets[i].name.toLowerCase())) {
                    this.tickets[i].errors.nameRepeat = true;
                    return false;
                }
                previous.push(this.tickets[i].name.toLowerCase())
            }
            return true;
        },

        initializeMoneyObject() {
            return {
                decimal: '.',
                thousands: '',
                prefix: '',
                suffix: '',
                precision: 2,
                masked: false  
            }
        },

        initializeTicketObject() {
            return {
                id: '',
                name: '',
                show_id: '',
                type: this.initializeTypeObject(),
                ticket_amount: '',
                ticket_price: '',
                errors: this.initializeErrorObject(),
                currency: this.currency,
            }
        },

        initializeTypeObject() {
            return {
                name: 'normal',
                type:'s',
            }
        },


        initializeErrorObject() {
            return {
                priceLength: false,
                priceEmpty: false,
                nameLength: false,
                nameEmpty: false,
                nameRepeat: false,
                descriptionLength:false,
            }
        },

    },

    watch: {
        '$store.state.navurl'() {
            this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
        },

        tickets() {
            this.checkTickets();
        }
    },

    created() {
        this.onLoad();
        this.disabled = false;
    },

    validations () {
        return {
            callAction: {
                required
            },
            ticketUrl: {
                required,
                url,
            },
            tickets: {
                required
            },
            selected: {

            }
        }
    },
}  
</script>