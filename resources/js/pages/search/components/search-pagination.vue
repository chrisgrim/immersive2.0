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
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </template>
                </button>
            </li>

            <li 
                v-for="(page, key) in pageRange" 
                :key="key" 
                :class="{ 'active': page === currentPage }">
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
                        <svg>
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                            </svg>
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