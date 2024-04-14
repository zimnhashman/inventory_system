<template>
    <div>
        <button @click="sortBy('name')">Sort by Name</button>
        <button @click="sortBy('price')">Sort by Price</button>
        <ul>
            <li v-for="item in sortedItems" :key="item.id">{{ item.name }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sortKey: '',
            sortOrder: 'asc', // or 'desc'
            items: [
                { id: 1, name: 'Item 2', price: 10 },
                { id: 2, name: 'Item 1', price: 5 },
                // More items...
            ],
        };
    },
    computed: {
        sortedItems() {
            if (this.sortKey) {
                return this.items.sort((a, b) => {
                    const modifier = this.sortOrder === 'desc' ? -1 : 1;
                    return modifier * (a[this.sortKey] > b[this.sortKey] ? 1 : -1);
                });
            } else {
                return this.items;
            }
        },
    },
    methods: {
        sortBy(key) {
            if (this.sortKey === key) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortOrder = 'asc';
            }
        },
    },
};
</script>
