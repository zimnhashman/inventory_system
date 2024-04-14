<template>
    <div>
        <!-- Display list of categories -->
        <ul>
            <li v-for="category in categories" :key="category.id">
                {{ category.name }}
                <button @click="editCategory(category)">Edit</button>
                <button @click="deleteCategory(category)">Delete</button>
            </li>
        </ul>

        <!-- Form to add/edit categories -->
        <form @submit.prevent="saveCategory">
            <input type="text" v-model="form.name" placeholder="Category name" required>
            <button type="submit">{{ editingCategory ? 'Update' : 'Add' }} Category</button>
        </form>
    </div>
</template>

<script>
import apiClient from './axios';

export default {
    data() {
        return {
            items: [],
            categories: [],
            form: {
                name: '',
                quantity: 0,
                category_id: null, // Assuming category_id is the foreign key for category
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
        filteredItems() {
            return this.items
                .filter(item => this.selectedCategory ? item.category_id === this.selectedCategory : true)
                .filter(item => this.searchQuery ? item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) : true);
        },
        paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredItems
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
        fetchItems() {
            apiClient.get('/items')
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });

            apiClient.get('/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        saveItem() {
            if (this.editingItem) {
                apiClient.put(`/items/${this.editingItem.id}`, this.form)
                    .then(response => {
                        // Handle update success
                        this.fetchItems(); // Refresh item list
                        this.clearForm();
                    })
                    .catch(error => {
                        console.error('Error updating item:', error);
                    });
            } else {
                apiClient.post('/items', this.form)
                    .then(response => {
                        // Handle creation success
                        this.fetchItems(); // Refresh item list
                        this.clearForm();
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
            this.form.category_id = item.category_id;
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
        clearForm() {
            this.form.name = '';
            this.form.quantity = 0;
            this.form.category_id = null;
            this.editingItem = null;
        },
    },
    mounted() {
        this.fetchItems();
    },
};
</script>


<style scoped>
/* CSS styles for the component */
</style>
