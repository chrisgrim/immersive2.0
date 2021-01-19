<template>
    <div class="create-menu__item" :class="{active: active.active}">
        <div class="name">
            <p class="create-menu-text">{{name}}</p>
        </div>
        <div class="status-icon">
            <div v-if="iconstatus == 'completed'">
                 <svg viewBox="0 0 32 32" role="img" aria-label="Completed" focusable="false" style="height: 20px; width: 20px; display: block; fill: rgb(0, 132, 137);"><path d="m21.71 13.71-6 6c-.39.39-1.02.39-1.41 0l-3-3c-.39-.39-.39-1.02 0-1.41s1.02-.39 1.41 0l2.29 2.29 5.29-5.29c.39-.39 1.02-.39 1.41 0s .39 1.02 0 1.41m-5.71-13.71c-8.84 0-16 7.16-16 16s7.16 16 16 16 16-7.16 16-16-7.16-16-16-16"></path></svg>
            </div>
            <div v-if="iconstatus == 'uncomplete'">
               <div class="create-nav-icon-border" style="width: 20px; height: 20px; border-color: rgb(176, 176, 176); color: rgb(176, 176, 176);"><svg viewBox="0 0 24 24" role="img" aria-label="Section collapsed. Click to expand." focusable="false" style="height: 100%; width: 100%; display: block; fill: rgb(176, 176, 176);"><rect height="2" rx="1" width="12" x="6" y="11"></rect><rect height="12" rx="1" width="2" x="11" y="6"></rect></svg></div>
            </div>
            <div v-if="iconstatus == 'locked'">
                <div class="create-nav-icon-border" style="width: 20px; height: 20px; border-color: rgb(176, 176, 176); color: rgb(176, 176, 176);"><svg viewBox="0 0 19 19" role="img" aria-label="This step is locked" focusable="false" style="height: 75%; width: 75%; display: block; fill: rgb(176, 176, 176);"><path d="m17.1403105 10.3322131v-1.83599021c-.0044371-2.48293439-2.0786109-4.49407316-4.6371478-4.49622289h-1.0063254c-2.56010616.00215385-4.6349284 2.01566919-4.63714782 4.50012925v1.83208385c-1.0387857.0478557-1.85657082.8775506-1.85968948 1.886773v6.8825219c0 1.0485082.87586388 1.898492 1.95629672 1.898492h10.08740658c1.0804328 0 1.9562967-.8499838 1.9562967-1.898492v-6.8825219c-.0031187-1.0092224-.8209038-1.8389173-1.8596895-1.886773zm-7.90518974-1.83599021c.00267117-2.2124746 1.58801224-2.19412124 2.26171654-2.19488726h1.0063254c.7450523.00084715 2.2590036-.05221743 2.2617165 2.19488726v1.83599021h-5.52975844z"></path></svg></div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {

        props: ['name', 'iconstatus', 'active'],

        data() {
            return {
               

            }
        },

    }
</script>