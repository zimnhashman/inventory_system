<template>
    <div>
        <!-- Search input -->
        <input type="text" v-model="searchQuery" placeholder="Search..." @input="searchItems">

        <!-- Filter by category -->
        <select v-model="selectedCategory" @change="filterItems">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>

        <!-- Display paginated items -->
        <ul>
            <li v-for="item in paginatedItems" :key="item.id">
                {{ item.name }} - {{ item.quantity }} in stock
                <button @click="editItem(item)">Edit</button>
                <button @click="deleteItem(item)">Delete</button>
            </li>
        </ul>

        <!-- Pagination controls -->
        <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
        <span>{{ currentPage }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>

        <!-- Sorting buttons -->
        <button @click="sortBy('name')">Sort by Name</button>
        <button @click="sortBy('quantity')">Sort by Quantity</button>

        <!-- Form to add/edit items -->
        <form @submit.prevent="saveItem">
            <input type="text" v-model="form.name" placeholder="Item name" required>
            <input type="number" v-model="form.quantity" placeholder="Quantity" required>
            <button type="submit">{{ editingItem ? 'Update' : 'Add' }} Item</button>
        </form>
    </div>
</template>


<script>
import apiClient from './axios';

export default {
    data() {
        return {
            items: [],
            form: {
                name: '',
                quantity: 0,
            },
            editingItem: null,
            searchQuery: '',
            selectedCategory: '',
            currentPage: 1,
            itemsPerPage: 5,
            sortKey: '',
            sortOrder: 'asc',
        };
    },
    computed: {
        paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            return this.items
                .filter(item => this.selectedCategory ? item.categoryId === this.selectedCategory : true)
                .filter(item => this.searchQuery ? item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) : true)
                .sort((a, b) => {
                    if (this.sortKey) {
                        const modifier = this.sortOrder === 'desc' ? -1 : 1;
                        return modifier * (a[this.sortKey] > b[this.sortKey] ? 1 : -1);
                    } else {
                        return 0;
                    }
                })
                .slice(startIndex, startIndex + this.itemsPerPage);
        },
        totalPages() {
            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
        },
    },
    methods: {
        fetchPreviousSearchTerms() {
            axios.get('/api/previous-search-terms')
                .then(response => {
                    this.previousSearchTerms = response.data;
                })
                .catch(error => {
                    console.error('Error fetching previous search terms:', error);
                });
        },
        fetchItems() {
            apiClient.get('/items')
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });
        },
        saveItem() {
            if (this.editingItem) {
                apiClient.put(`/items/${this.editingItem.id}`, this.form)
                    .then(response => {
                        // Handle update success
                        this.fetchItems(); // Refresh item list
                    })
                    .catch(error => {
                        console.error('Error updating item:', error);
                    });
            } else {
                apiClient.post('/items', this.form)
                    .then(response => {
                        // Handle creation success
                        this.fetchItems(); // Refresh item list
                    })
                    .catch(error => {
                        console.error('Error creating item:', error);
                    });
            }
        },
        editItem(item) {
            this.editingItem = item;
            this.form.name = item.name;
            this.form.quantity = item.quantity;
        },
        deleteItem(item) {
            apiClient.delete(`/items/${item.id}`)
                .then(response => {
                    // Handle deletion success
                    this.fetchItems(); // Refresh item list
                })
                .catch(error => {
                    console.error('Error deleting item:', error);
                });
        },
        searchItems() {
            this.currentPage = 1; // Reset pagination when searching
        },
        filterItems() {
            this.currentPage = 1; // Reset pagination when filtering
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        sortBy(key) {
            if (this.sortKey === key) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortOrder = 'asc';
            }
        },
    },
    mounted() {
        this.fetchPreviousSearchTerms();
        this.fetchItems();
    },

};
</script>


<style scoped>
/* CSS styles for the component */
</style>
