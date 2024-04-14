<template>
    <div>
        <select v-model="selectedCategory">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
        <ul>
            <li v-for="item in filteredItems" :key="item.id">{{ item.name }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedCategory: '',
            categories: [
                { id: 1, name: 'Category 1' },
                { id: 2, name: 'Category 2' },
            ],
            items: [
                { id: 1, name: 'Item 1', categoryId: 1 },
                { id: 2, name: 'Item 2', categoryId: 2 },
            ],
        };
    },
    computed: {
        filteredItems() {
            return this.items.filter(item =>
                (!this.selectedCategory || item.categoryId == this.selectedCategory)
            );
        },
    },
};
</script>
