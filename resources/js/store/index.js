import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
    state: {
        posts: [],
        token: localStorage.getItem('token') || '',
    },
    mutations: {
        setPosts(state, posts) {
            state.posts = posts;
        },
        addPost(state, post) {
            state.posts.push(post);
        },
        setToken(state, token) {
            state.token = token;
        },
        deletePost(state, postId) {
            state.posts = state.posts.filter(post => post.id !== postId);
        },
        updatePost(state, updatedPost) {
            const index = state.posts.findIndex(post => post.id === updatedPost.id);
            if (index !== -1) {
                state.posts.splice(index, 1, updatedPost);
            }
        },
    },
    actions: {
        async login({ commit, dispatch }, credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const token = response.data.access_token;
                localStorage.setItem('token', token);
                commit('setToken', token);
                dispatch('fetchPosts');
            } catch (error) {
                throw new Error('Invalid email or password');
            }
        },

        async fetchPosts({ commit, state }) {
            try {
                const response = await axios.get('/api/posts', {
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                    },
                });
                commit('setPosts', response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async createPost({ commit, state }, postData) {
            try {
                const formData = new FormData();
                formData.append('title', postData.title);
                formData.append('content', postData.content);

                const response = await axios.post('/api/posts', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${state.token}`,
                    },
                });
                commit('addPost', response.data);
            } catch (error) {
                console.error('Error creating post:', error);
            }
        },
        async deletePost({ commit, state }, postId) {
            try {
                await axios.delete(`/api/posts/${postId}`, {
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                    },
                });
                commit('deletePost', postId);
            } catch (error) {
                console.error('Error deleting post:', error);
            }
        },
        async updatePost({ commit, state }, updatedPost) {
            try {
                const response = await axios.put(`/api/posts/${updatedPost.id}`, updatedPost, {
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                    },
                });
                commit('updatePost', response.data);
            } catch (error) {
                console.error('Error updating post:', error);
            }
        },
    },
});

export default store;
