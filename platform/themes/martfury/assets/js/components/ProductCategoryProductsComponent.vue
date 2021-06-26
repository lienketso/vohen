<template>
        <div class="ps-container">
            <div class="ps-section__header">
                <h3>{{ category.name }}</h3>
                <ul class="ps-section__links">
                    <li v-for="item in productCategories" :key="item.id">
                        <a :class="productCategory.id === item.id ? 'active': ''" :id="item.slug + '-tab'" data-toggle="tab" :href="'#' + item.slug" role="tab" :aria-controls="item.slug" aria-selected="true" @click="getData(item)">{{ item.name }}</a>
                    </li>
                    <li><a :href="all">{{ __('View All') }}</a></li>
                </ul>
            </div>
            <div class="ps-section__content">
                <div class="row">
                <div class="col-lg-3">
                    <div class="ads_category">
                        <a href="#"><img src="https://image.voso.vn/users/vosoimage/images/a955076a1a61e910c08354b76b23d741?t%5B0%5D=maxSize%3Awidth%3D590%2Cheight%3D1240&t%5B1%5D=compress%3Alevel%3D100&accessToken=b4c86e427f554eb1ad01eae3ff7963a08946f3edd2f7596f903c7390fe55dbcb"></a>
                    </div>
                </div>

                <div class="col-lg-9 bg-white">
                <div class="half-circle-spinner" v-if="isLoading">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-2"></div>
                </div>
                <div class="tab-pane fade show active" v-if="!isLoading" :id="productCategory.slug"
                     role="tabpanel" :aria-labelledby="productCategory.slug + '-tab'" :key="productCategory.id">
                    <div class="row ">

                        <div class="col-lg-2 col-6 col-half-offset ps-product" v-for="item in data" :key="item.id" v-if="data.length" v-html="item"></div>

                    </div>
                </div>
                </div>
                </div>

            </div>
        </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: [],
                productCategory: {},
                productCategories: []
            };
        },

        mounted() {
            if (this.category) {
                this.productCategory = this.category;
                this.productCategories = this.children;
                this.getData(this.productCategory);
            }
        },

        props: {
            category: {
                type: Object,
                default: () => {},
                required: true
            },
            children: {
                type: Array,
                default: () => [],
            },
            url: {
                type: String,
                default: () => null,
                required: true
            },
            all: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getData(category) {
                this.productCategory = category;
                this.data = [];
                this.isLoading = true;
                axios.get(this.url + '?category_id=' + category.id)
                    .then(res => {
                        this.data = res.data.data ? res.data.data : [];
                        this.isLoading = false;
                    })
                    .catch(res => {
                        this.isLoading = false;
                        console.log(res);
                    });
            },
        },
    }
</script>
