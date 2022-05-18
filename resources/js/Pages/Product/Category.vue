<template>
<layout :categorys2="categorys2">
    <div>
        <!--  Featured category start -->
            <div class="product-tab-section mb-50">
                <div class="container-fluid plr-200">
                    <div class="row">
                        <div class="col-lg-12 section-title text-center mb-40 mt-40">
                            <h2 class="uppercase">Explore Categories</h2>
                        </div>
                    </div>
                    <div class="row">
                        <ul>
                              <li v-for="(category, index) in categories" :key="index" class="mytab" >
                               <inertia-link :href="`/category/`+category.slug" data-toggle="tab"> 
                                <div class="cat_img"> 
                                   <img v-if="category.image" :src="parent_url()+`images/category/`+category.image" alt="Image Not Loaded">
                                   <img v-else :src="parent_url()+`images/no-image-available.png`" alt="Image Not Loaded">  
                                </div> 
                                <div class="cat_name">{{category.name}}</div>
                            </inertia-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--Featured category end-->

    </div>
</layout>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import Paginator from "@/Components/Paginator";
    import FilterBar from "@/Pages/Product/FilterBar";
    import ProductModal from "@/Pages/Product/ProductModal";
    
    export default {
        props: ['categories','categorys2'],
        components:{
            Layout,Paginator,FilterBar,ProductModal
        },
        methods: {
           
        }
        
    }
</script>