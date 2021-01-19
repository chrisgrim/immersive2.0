<template>
    <div 
        class="nav-dropdown" 
        :style="`background:${user.hexColor}`" 
        ref="dropdown">
        <button 
            @click="toggle" 
            class="nav-dropdown--icon">
            <label v-if="user.largeImagePath">
                <picture>
                    <source 
                        height="28" 
                        width="28" 
                        type="image/webp" 
                        :srcset="`/storage/${user.thumbImagePath}`"> 
                    <img 
                        height="28" 
                        width="28" 
                        :src="`/storage/${user.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="user.name + `'s account`">
                </picture>
            </label>
            <template v-else-if="user.gravatar">
                <img 
                    :src="user.gravatar" 
                    height="28" 
                    width="28" 
                    :alt="user.name + `'s account`">
            </template>
            <template v-else>
                <h2>
                    {{ userName.charAt(0) }}
                </h2>
            </template>
        </button>
        <ul 
            v-show="onToggle" 
            class="sub-dropdown">
            <li>
                <a :href="'/users/'+ url">
                    Profile
                </a>
            </li>
            <li>
                <a href="/account-settings">
                    Account
                </a>
            </li>
            <li v-if="isModerator">
                <a href="/admin/dashboard">
                    Admin Dashboard
                </a>
            </li>
            <li>
                <a 
                    href="#" 
                    @click="logout()">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
	export default {
        props: ['user', 'screenwidth'],

		data() {
			return {
				avatar: this.user.thumbImagePath ? `/storage/${this.user.thumbImagePath}` : '',
				userName: this.user.name,
				url: this.user.id,
				onToggle:false,
                isModerator: this.user && this.user.type == 'a' || this.user && this.user.type == 'm'
			};
		},


		methods: {
			logout(){
				axios.post('/logout').
					then(res => {
                        window.location.href = '/';
					});
            }, 

            toggle() {
                if (this.screenwidth) {
                    return window.location.href = `/account-settings`;
                }
                return this.onToggle = !this.onToggle;
            },

            onClickOutside(event) {
                let arr =  this.$refs.dropdown;
                if (!arr || arr.contains(event.target)) return;
                this.onToggle = false;
            },
		},

        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        }
	}
</script>



