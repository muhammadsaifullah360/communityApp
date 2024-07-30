<template>
    <div class="scroll-container" @scroll="handleScroll">
        <div v-for="post in posts" :key="post.id" class="post">
            <p><strong>{{ post.title }}</strong></p>
            <p>{{ post.content }}</p>
            <div class="icons">
                <span class="thumb-icon" @click="toggleThumb(post)">
                    <i :class="{'checked': post.checked}">👍</i>
                </span>
                <span class="comment-icon" @click="toggleComment(post)">
                    💬
                </span>
                <small>{{ formatDate(post.created_at) }}</small>
            </div>
            <div v-if="post.showComment" class="comment-area">
                <textarea placeholder="Write a comment..."></textarea>
            </div>
        </div>
        <div v-if="loading" class="loading">Loading...</div>
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
                    // Initialize the new posts with checked and showComment properties
                    this.posts.forEach(post => {
                        if (!post.hasOwnProperty('checked')) post.checked = false;
                        if (!post.hasOwnProperty('showComment')) post.showComment = false;
                    });
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
        },
        toggleThumb(post) {
            post.checked = !post.checked;
        },
        toggleComment(post) {
            post.showComment = !post.showComment;
        }
    }
};
</script>

<style scoped>
.scroll-container {
    height: 80vh;
    overflow-y: auto;
}

.post {
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
}

.icons {
    display: flex;
    align-items: center;
}

.thumb-icon {
    cursor: pointer;
    margin-right: 10px;
}

.thumb-icon .checked {
    background-color: blue;
    color: white;
    border-radius: 50%;
    padding: 5px;
}

.comment-icon {
    cursor: pointer;
    margin-right: 10px;
}

.comment-area {
    margin-top: 10px;
}

.loading {
    text-align: center;
    padding: 10px;
}
</style>
