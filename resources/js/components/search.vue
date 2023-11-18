<template>
    <input type="text" placeholder="Search here..." id="pc_search" v-model="search"> <button @click="pc_search"><i class="fa-solid fa-magnifying-glass"></i></button>
</template>

<script>
export default {
    data() {
        return {
            name: 'pc-search'
        }
    },
    methods: {
        pc_search() {
            
            location.href = "/search/"+this.search.replace(" ", '-');
        }
    },
}
</script>