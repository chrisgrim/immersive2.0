<template>
    <div class="pagination">
        <ul 
            v-if="total > perPage"
            class="pagination">
            <li v-if="prevPageUrl">
                <button
                    class="paginate__arrows"
                    :tabindex="!prevPageUrl && -1" 
                    @click="previousPage">
                    <template>
                        <svg 
                            viewBox="0 0 32 32" 
                            xmlns="http://www.w3.org/2000/svg" 
                            style="display:block;fill:none;height:16px;width:16px;stroke:currentColor;stroke-width:4;overflow:visible" 
                            aria-hidden="true" 
                            role="presentation" 
                            focusable="false">
                            <g fill="none">
                                <path d="m20 28-11.29289322-11.2928932c-.39052429-.3905243-.39052429-1.0236893 0-1.4142136l11.29289322-11.2928932" />
                            </g>
                        </svg>
                    </template>
                </button>
            </li>

            <li 
                v-for="(page, key) in pageRange" 
                :key="key" 
                :class="{ 'active': page == currentPage }">
                <button @click="selectPage(page)">
                    {{ page }}
                </button>
            </li>

            <li 
                :class="{'disabled': !nextPageUrl}" 
                v-if="nextPageUrl">
                <button
                    class="paginate__arrows"
                    :tabindex="!nextPageUrl && -1" 
                    @click="nextPage">
                    <slot name="next-nav">
                        <svg 
                            viewBox="0 0 32 32" 
                            xmlns="http://www.w3.org/2000/svg" 
                            style="display:block;fill:none;height:16px;width:16px;stroke:currentColor;stroke-width:4;overflow:visible" 
                            aria-hidden="true" 
                            role="presentation" 
                            focusable="false">
                            <g fill="none">
                                <path d="m12 4 11.2928932 11.2928932c.3905243.3905243.3905243 1.0236893 0 1.4142136l-11.2928932 11.2928932" />
                            </g>
                        </svg>
                    </slot>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {

        props:['list', 'limit'],

        computed: {
            currentPage () {
                return this.list.current_page;
            },

            firstPageUrl () {
                return this.list.first_page_url;
            },

            nextPageUrl () {
                return this.list.next_page_url;
            },

            prevPageUrl () {
                return this.list.prev_page_url;
            },

            from () {
                return this.list.from;
            },

            lastPage () {
                return this.list.last_page;
            },

            total () {
                return this.list.total;
            },

            perPage () {
                return this.list.per_page;
            },

            pageRange () {
                if (this.limit === -1) {
                    return 0;
                }
                if (this.limit === 0) {
                    return this.lastPage;
                }
                var current = this.currentPage;
                var last = this.lastPage;
                var delta = this.limit;
                var left = current - delta;
                var right = current + delta + 1;
                var range = [];
                var pages = [];
                var l;
                for (var i = 1; i <= last; i++) {
                    if (i === 1 || i === last || (i >= left && i < right)) {
                        range.push(i);
                    }
                }
                range.forEach(function (i) {
                    if (l) {
                        if (i - l === 2) {
                            pages.push(l + 1);
                        } else if (i - l !== 1) {
                            pages.push('...');
                        }
                    }
                    pages.push(i);
                    l = i;
                });
                return pages;
            }
        },

        methods: {

            previousPage () {
                this.selectPage((this.currentPage - 1));
            },

            nextPage () {
                this.selectPage((this.currentPage + 1));
            },

            selectPage (page) {
                this.$emit('selectpage', page);
            },

        },
    }
</script>