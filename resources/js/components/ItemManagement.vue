<template>
    <div>
        <!-- Display list of items -->
        <ul>
            <li v-for="item in items" :key="item.id">
                {{ item.name }} - {{ item.quantity }} in stock
                <button @click="editItem(item)">Edit</button>
                <button @click="deleteItem(item)">Delete</button>
            </li>
        </ul>

        <!-- Form to add/edit items -->
        <form @submit.prevent="saveItem">
            <input type="text" v-model="form.name" placeholder="Item name" required>
            <input type="number" v-model="form.quantity" placeholder="Quantity" required>
            <button type="submit">{{ editingItem ? 'Update' : 'Add' }} Item</button>
        </form>
    </div>
</template>
// ItemManagement.vue
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
        };
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
    },
    mounted() {
        this.fetchItems();
    },
};
</script>


<style scoped>
/* CSS styles for the component */
</style>
