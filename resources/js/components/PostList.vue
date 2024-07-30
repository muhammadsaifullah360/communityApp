<template>
    <div class="scroll-container" @scroll="handleScroll">
        <div v-for="post in posts" :key="post.id" class="post">
            <p><strong>{{ post.title }}</strong></p>
            <p>{{ post.content }}</p>
            <small>{{ formatDate(post.created_at) }}</small>
        </div>
        <!-- <div v-if="loading" class="loading">Loading...</div> -->
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            posts: [],
            page: 1,
            loading: false,
            finished: false
        };
    },
    async created() {
        await this.fetchPosts();
    },
    methods: {
        handleScroll() {
            const container = this.$el;
            const bottom = container.scrollHeight === container.scrollTop + container.clientHeight;
            if (bottom && !this.loading && !this.finished) {
                this.fetchPosts();
            }
        },
        async fetchPosts() {
            if (this.loading || this.finished) return;

            this.loading = true;
            try {
                const response = await axios.get(`/api/posts?page=${this.page}`);
                if (response.data.data.length === 0) {
                    this.finished = true;
                } else {
                    this.posts = [...this.posts, ...response.data.data];
                    this.page++;
                }
            } catch (error) {
                console.error('Error fetching posts:', error);
            } finally {
                this.loading = false;
            }
        },
        async refreshPosts() {
            this.posts = [];
            this.page = 1;
            this.finished = false;
            await this.fetchPosts();
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();

            return `${hours}:${minutes} ${day}-${month}-${year}`;
        }
    }
};
</script>

<style scoped>
.scroll-container {
    height: 80vh;
    overflow-y: auto;
}

.loading {
    text-align: center;
    padding: 10px;
}
</style>
