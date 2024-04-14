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
import {useCategoriesStore} from '../store/categories'; // Import your Pinia store
import apiClient from './axios';

export default {
    data() {
        return {
            form: {
                name: '',
            },
            editingCategory: null,
        };
    },
    computed: {
        categories() {
            return useCategoriesStore().categories;
        },
    },
    methods: {
        saveCategory() {
            const store = useCategoriesStore();
            if (this.editingCategory) {
                apiClient.put(`/categories/${this.editingCategory.id}`, this.form)
                    .then(response => {
                        // Handle update success
                        store.editCategory(response.data); // Update category in store
                        this.clearForm();
                    })
                    .catch(error => {
                        console.error('Error updating category:', error);
                    });
            } else {
                apiClient.post('/categories', this.form)
                    .then(response => {
                        // Handle creation success
                        store.addCategory(response.data); // Add new category to store
                        this.clearForm();
                    })
                    .catch(error => {
                        console.error('Error creating category:', error);
                    });
            }
        },
        editCategory(category) {
            this.editingCategory = category;
            this.form.name = category.name;
        },
        deleteCategory(category) {
            apiClient.delete(`/categories/${category.id}`)
                .then(response => {
                    // Handle deletion success
                    useCategoriesStore().deleteCategory(category.id); // Remove category from store
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                });
        },
        clearForm() {
            this.form.name = '';
            this.editingCategory = null;
        },
    },
    mounted() {
        // Fetch categories if needed on component mount
        // apiClient.get('/categories')
        //     .then(response => {
        //         useCategoriesStore().setCategories(response.data);
        //     })
        //     .catch(error => {
        //         console.error('Error fetching categories:', error);
        //     });
    },
};
</script>

<style scoped>
/* CSS styles for the component */
</style>
