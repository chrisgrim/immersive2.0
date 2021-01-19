import { throttle } from 'lodash';

export default {
    template: '<div></div>',

    data: () => ({
        observer: null,
    }),

    mounted() {
        const options = this.options || {};
        this.observer = new IntersectionObserver(([entry]) => {
          if (entry && entry.isIntersecting) {
            this.$emit("intersect");
          }
        }, options);

        this.observer.observe(this.$el);
    },
    destroyed() {
        this.observer.disconnect();
    },

    methods: {
       
    }
}