<template>
    <div>
        <ul>
            <li v-for="item in paginatedItems" :key="item.id">{{ item.name }}</li>
        </ul>
        <button @click="prevPage">Previous</button>
        <button @click="nextPage">Next</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentPage: 1,
            itemsPerPage: 5,
            items: [
                { id: 1, name: 'Item 1' },
                { id: 2, name: 'Item 2' },
                // More items...
            ],
        };
    },
    computed: {
        paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            return this.items.slice(startIndex, startIndex + this.itemsPerPage);
        },
    },
    methods: {
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            const totalPages = Math.ceil(this.items.length / this.itemsPerPage);
            if (this.currentPage < totalPages) {
                this.currentPage++;
            }
        },
    },
};
</script>
