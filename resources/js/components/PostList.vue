<template>
    <div ref="scrollContainer" class="scroll-container" @scroll="handleScroll">
        <div v-for="post in posts" :key="post.id" class="post">
            <p><strong>{{ post.title }}</strong></p>
            <p>{{ post.content }}</p>
            <div class="icons">
                <span class="thumb-icon" @click="toggleThumb(post)">
                    <i :class="{ 'checked': post.checked }">👍</i>
                    <small>{{ post.likes_count }}</small>
                </span>
                <span class="comment-icon" @click="toggleComment(post)">
                    💬
                </span>
                <small>{{ formatDate(post.created_at) }}</small>
            </div>
            <div v-if="post.showComment" class="comment-area">
                <textarea v-model="post.newComment" placeholder="Write a comment..."></textarea>
                <button @click="submitComment(post)">Send</button>
                <div v-if="post.commentSent" class="comment-success">Comment sent!</div>
            </div>
            <div v-if="post.comments && post.comments.length" class="comments-list">
                <div v-for="comment in post.comments" :key="comment.id" class="comment">
                    <strong>{{ comment.user.name }}:</strong> {{ comment.content }}
                </div>
            </div>
        </div>
        <div v-if="loading" class="loading">Loading...</div>
        <div v-if="finished && !loading" class="loading">No more posts to load.</div>
    </div>
</template>

<script>
import { formatDistanceToNow, parseISO } from 'date-fns';
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
    async mounted() {
        await this.fetchPosts();
        if (this.$refs.scrollContainer) {
            this.$refs.scrollContainer.addEventListener('scroll', this.handleScroll);
        }
    },
    beforeUnmount() {
        if (this.$refs.scrollContainer) {
            this.$refs.scrollContainer.removeEventListener('scroll', this.handleScroll);
        }
    },
    methods: {
        handleScroll() {
            const container = this.$refs.scrollContainer;
            if (container) {
                const scrollTop = container.scrollTop;
                const clientHeight = container.clientHeight;
                const scrollHeight = container.scrollHeight;
                const nearBottom = scrollHeight - scrollTop <= clientHeight + 100; // buffer of 100px

                if (nearBottom && !this.loading && !this.finished) {
                    this.fetchPosts();
                }
            }
        },
        async fetchPosts() {
            if (this.loading || this.finished) return;

            this.loading = true;
            try {
                const response = await axios.get(`/api/get/posts?page=${this.page}`);
                const { data, current_page, last_page } = response.data;

                if (!Array.isArray(data)) {
                    throw new Error('Unexpected response format: not an array');
                }

                if (data.length === 0 || current_page >= last_page) {
                    this.finished = true;
                } else {
                    this.posts = [...this.posts, ...data];
                    this.posts.forEach(post => {
                        if (!post.hasOwnProperty('checked')) post.checked = false;
                        if (!post.hasOwnProperty('showComment')) post.showComment = false;
                        if (!post.hasOwnProperty('newComment')) post.newComment = '';
                        if (!post.hasOwnProperty('commentSent')) post.commentSent = false;
                        if (!post.hasOwnProperty('comments')) post.comments = []; // Initialize comments if missing
                    });
                    this.page++;
                }
            } catch (error) {
                console.error('Error fetching posts:', error);
            } finally {
                this.loading = false;
            }
        },
        async toggleThumb(post) {
            try {
                const headers = {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                };

                if (post.checked) {
                    await axios.post(`/api/posts/${post.id}/unlike`, {}, { headers });
                    post.likes_count--;
                } else {
                    await axios.post(`/api/posts/${post.id}/like`, {}, { headers });
                    post.likes_count++;
                }
                post.checked = !post.checked;
            } catch (error) {
                console.error('Error toggling thumb:', error);
            }
        },
        toggleComment(post) {
            post.showComment = !post.showComment;
            if (post.showComment && !post.comments.length) {
                this.fetchComments(post);
            }
        },
        async fetchComments(post) {
            try {
                const headers = {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                };
                const response = await axios.get(`/api/posts/${post.id}/comments`);
                post.comments = response.data;
            } catch (error) {
                console.error('Error fetching comments:', error);
            }
        },
        async submitComment(post) {
            try {
                await axios.post(`/api/posts/${post.id}/comment`, { content: post.newComment });
                post.newComment = '';
                post.commentSent = true;
                await this.fetchComments(post); // Refresh comments
            } catch (error) {
                console.error('Error submitting comment:', error);
            }
        },
        formatDate(dateString) {
            const date = parseISO(dateString);
            return formatDistanceToNow(date, { addSuffix: true });
        },
        refreshPosts() {
            this.posts = [];
            this.page = 1;
            this.finished = false;
            this.fetchPosts();
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
    display: flex;
    align-items: center;
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

.comments-list {
    margin-top: 10px;
}

.comment {
    border-bottom: 1px solid #eee;
    padding: 5px 0;
}

.comment-success {
    color: green;
    margin-top: 5px;
}
</style>
