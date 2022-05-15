<template>

<div v-if="paginator!==undefined" >
    <div class="product-pagination">
        <div class="theme-paggination-block">
            <div class="row">
                <div class="col-xl-8 col-md-8 col-sm-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li  class="page-item" v-if="onFirstPage" aria-disabled="true"></li>
                            <li  class="page-item" v-else><inertia-link :href="previousPageUrl" rel="prev"><a class="page-link">Previous</a></inertia-link></li>
                            <li  class="page-item" v-for="link in paginator.links" :key="link">
                                <inertia-link v-if="!isFirstOrLastOrDots(link.label)" :class="{'active' : link.active===true}" :href="link.url">
                                    <a class="page-link">{{ link.label }}</a>
                                </inertia-link>
                                <span v-else-if="link.label==='...'" aria-disabled="true">
                                    <p class="pagination_dot"> {{ link.label }}...</p>
                                </span>
                            </li>
                            <li class="page-item" v-if="hasMorePages"><inertia-link  :href="nextPageUrl"><a class="page-link">Next</a></inertia-link></li>
                            <li  class="page-item" v-else aria-disabled="true"></li>
                        </ul>
         
                    </nav>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-12">
                    <div class="product-search-count-bottom">
                        <h5>Showing Items  <span v-text="firstItem"></span>-<span v-text="lastItem"></span> of <span v-text="total"></span> Result</h5></div>
                </div>
            </div>
        </div>
    </div>
  </div>



</template>

<script>
export default {
    name: "Paginator",
    props: {
        paginator: {
            current_page: Number,
            data: Array,
            first_page_url: String,
            from: Number,
            last_page: Number,
            last_page_url: String,
            links: Array,
            next_page_url: String,
            path: String,
            per_page: Number,
            prev_page_url: String,
            to: Number,
            total: Number,
        }
    },

    methods: {
        isFirstOrLastOrDots(label) {
            return label.includes('Next') || label.includes('Previous') || label.includes('...')
        },
    },

    computed: {
        onFirstPage() {
            return this.paginator.current_page === 1;
        },

        hasMorePages() {
            return this.paginator.current_page < this.paginator.last_page;
        },

        nextPageUrl() {
            return this.paginator.next_page_url;
        },

        previousPageUrl() {
            return this.paginator.prev_page_url;
        },

        firstItem() {
            return this.paginator.from;
        },

        lastItem() {
            return this.paginator.to;
        },

        total() {
            return this.paginator.total;
        },
    }
}
</script>

