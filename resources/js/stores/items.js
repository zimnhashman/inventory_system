// src/store/items.js
import { defineStore } from 'pinia';
import apiClient from '../apiClient'; // Your API client

export const useItemsStore = defineStore({
    id: 'items',
    state: () => ({
        items: [],
        searchQuery: '',
        selectedCategory: '',
        currentPage: 1,
        itemsPerPage: 5,
        sortKey: '',
        sortOrder: 'asc',
    }),
    actions: {
        fetchItems() {
            return apiClient.get('/items')
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });
        },
        saveItem(itemData) {
            return apiClient.post('/items', itemData)
                .then(response => {
                    this.items.push(response.data); // Assuming response.data contains the saved item
                })
                .catch(error => {
                    console.error('Error saving item:', error);
                });
        },
        editItem(itemId, updatedItemData) {
            return apiClient.put(`/items/${itemId}`, updatedItemData)
                .then(response => {
                    const index = this.items.findIndex(item => item.id === itemId);
                    if (index !== -1) {
                        this.items[index] = response.data; // Update the item in the items array
                    }
                })
                .catch(error => {
                    console.error('Error updating item:', error);
                });
        },
        deleteItem(itemId) {
            return apiClient.delete(`/items/${itemId}`)
                .then(() => {
                    this.items = this.items.filter(item => item.id !== itemId); // Remove the item from items array
                })
                .catch(error => {
                    console.error('Error deleting item:', error);
                });
        },
    },
});
