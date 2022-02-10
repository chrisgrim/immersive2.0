<template>
    <div class="relative flex w-100 bg-none min-h-[calc(100vh-7rem)] md:items-center">
        <div class="rounded-5xl w-[55rem] max-h-[90vh] m-auto border overflow-auto">
            <div class="p-8 flex w-full relative h-24 justify-evenly border-b">
                <p>Register</p>
            </div>
            <div class="p-8">
                <RegisterForm />
            </div>
        </div>
    </div>
</template>

<script>
    import RegisterForm from './Components/register-form'
    export default {
        components: { RegisterForm },
    }
</script>