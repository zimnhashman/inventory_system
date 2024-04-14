<!-- resources/js/components/ItemForm.vue -->

<template>
    <div>
        <h2>{{ editingItem ? 'Edit Item' : 'Add Item' }}</h2>

        <!-- Form for adding/editing items -->
        <form @submit.prevent="saveItem">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="form.name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" v-model="form.description" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" v-model="form.price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" ref="image" accept="image/*" @change="handleImageChange" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">{{ editingItem ? 'Update' : 'Add' }} Item</button>
        </form>
    </div>
</template>

<script>
import apiClient from '../apiClient';
import { useStore } from 'pinia'; // Import useStore from Pinia
import { ref } from 'vue'; // Import ref for reactive variables

export default {
    setup() {
        const store = useStore(); // Use Pinia store

        // Reactive form fields
        const form = ref({
            name: '',
            description: '',
            price: 0,
            image: null,
        });

        const editingItem = ref(null); // Reactive editingItem

        const saveItem = () => {
            // Validate form fields here if needed
            if (editingItem.value) {
                // Handle item update
            } else {
                // Handle item creation
                store.dispatch('createItem', form.value).then(() => {
                    // Reset form after successful submission
                    resetForm();
                }).catch(error => {
                    console.error('Error creating item:', error);
                });
            }
        };

        const handleImageChange = (event) => {
            // Update form data with selected image file
            form.value.image = event.target.files[0];
        };

        const resetForm = () => {
            // Reset form fields after submission
            form.value.name = '';
            form.value.description = '';
            form.value.price = 0;
            form.value.image = null;
        };

        return { form, editingItem, saveItem, handleImageChange };
    },
};
</script>

<style scoped>
/* Add custom styles for the component */
</style>
