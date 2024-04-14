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
import { useStore } from 'pinia';
import { computed } from 'vue'; // Import computed from Vue

export default {
    setup() {
        const store = useStore(); // Use Pinia store

        // Use computed properties from Pinia store
        const searchQuery = computed(() => store.searchQuery);
        const selectedCategory = computed(() => store.selectedCategory);
        const currentPage = computed(() => store.currentPage);
        const itemsPerPage = computed(() => store.itemsPerPage);
        const sortKey = computed(() => store.sortKey);
        const sortOrder = computed(() => store.sortOrder);

        // Use Pinia store actions
        const {
            fetchItems,
            saveItem,
            editItem,
            deleteItem,
            searchItems,
            filterItems,
            prevPage,
            nextPage,
            sortBy
        } = store;

        // Use Pinia store state directly if needed
        const items = store.items;
        const form = {
            name: '',
            quantity: 0,
        };
        const editingItem = null; // Placeholder for editingItem

        return {
            items,
            form,
            editingItem,
            searchQuery,
            selectedCategory,
            currentPage,
            itemsPerPage,
            sortKey,
            sortOrder,
            fetchItems,
            saveItem,
            editItem,
            deleteItem,
            searchItems,
            filterItems,
            prevPage,
            nextPage,
            sortBy,
        };
    },
};
</script>

<style scoped>
/* CSS styles for the component */
</style>
