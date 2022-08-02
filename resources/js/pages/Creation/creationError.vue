<template>
    <div>
        <p 
            v-if="errors.hasOwnProperty('required') ? errors.required ? false : true : ''"
            class="">Must provide a {{item}}</p>
        <p 
            v-if="errors.hasOwnProperty('maxLength') ? errors.maxLength ? false : true : ''"
            class="">{{item}} is too long</p>
        <p 
            v-if="errors.hasOwnProperty('minLength') ? errors.minLength ? false : true : ''"
            class="">{{item}} is too short</p>
        <p 
            v-if="errors.hasOwnProperty('email') ? errors.email ? false : true : ''"
            class="">{{item}} must be an email</p>
        <p  
            v-if="errors.hasOwnProperty('fileSize') ? errors.fileSize ? false : true : ''"
            class="">
            The {{item}} file size is over {{size}}</p>
        <p 
            v-if="errors.hasOwnProperty('fileType') ? errors.fileType ? false : true : ''"
            class="">
            The {{item}} needs to be a JPG, PNG or GIF</p>
        <p 
            v-if="errors.hasOwnProperty('imageRatio') ? errors.imageRatio ? false : true : ''"
            class="">
            The {{item}} needs to be at least {{ width }} x {{ height }}</p>
        <p 
            v-if="errors.hasOwnProperty('imageRequired') ? errors.imageRequired ? false : true : ''"
            class="">
            An {{item}} is required</p>
        <div v-if="serverErrors.email && serverErrors.email.length">
            <p 
                v-for="error in serverErrors.email"
                :key="error"
                class="">
                {{error}}</p>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            errors: {
                type: Object,
                required: true
            },
            serverErrors: {
                type: Object,
                required: false,
                default() {
                    return {}
                }
            },
            item: {
                type: String,
                default: 'element'
            },
            size: {
                type: String,
                default: '10MB'
            },
            width: {
                type: String,
                default: '400'
            },
            height: {
                type: String,
                default: '400'
            },
        },

        data() {
            return {
                
            };
        },

    };
</script>