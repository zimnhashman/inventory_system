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
            categories: [],
            form: {
                name: '',
            },
            editingCategory: null,
        };
    },
    methods: {
        fetchCategories() {
            apiClient.get('/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        saveCategory() {
            if (this.editingCategory) {
                apiClient.put(`/categories/${this.editingCategory.id}`, this.form)
                    .then(response => {
                        // Handle update success
                        this.fetchCategories(); // Refresh category list
                    })
                    .catch(error => {
                        console.error('Error updating category:', error);
                    });
            } else {
                apiClient.post('/categories', this.form)
                    .then(response => {
                        // Handle creation success
                        this.fetchCategories(); // Refresh category list
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
                    this.fetchCategories(); // Refresh category list
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                });
        },
    },
    mounted() {
        this.fetchCategories();
    },
};
</script>

<style scoped>
/* CSS styles for the component */
</style>
