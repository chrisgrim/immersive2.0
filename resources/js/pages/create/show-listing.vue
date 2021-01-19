<template>
    <div>
      <li>
      {{ date }}
      <button v-on:click="$emit('remove')">Remove</button>
      <input type="text" :value="date">
      <flat-pickr
                v-model="date1"                                                  
                class="form-control" 
                placeholder="Select date"               
                name="date1">
            </flat-pickr>
      </li>

    </div>
</template>

<script>
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'


export default {

    props: ['date'],

    components: {
      flatPickr
    },

    data() {
        return {
            date1:'',
        }
    },

    methods: {
    }

      
}  
</script>