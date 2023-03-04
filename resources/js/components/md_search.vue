<template>
    <div class="search">
        <input type="text" name="" id="mobile_search" placeholder="Search here..." v-model="search">
    </div>
    <div class="search_button">
        <button @click="md_search"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: 'md-search'
        }
    },
    methods: {
        md_search() {

            location.href = "/search/"+this.search.replace(" ", '-');
        }
    },
}
</script>