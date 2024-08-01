// store/modules/posts.js
import axios from 'axios';

const state = {
    posts: []
};

const getters = {
    allPosts: (state) => state.posts,
};

const actions = {
    async fetchPosts({ commit }) {
        try {
            const token = localStorage.getItem('token');
            const response = await axios.get('/api/posts', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            commit('setPosts', response.data);
        } catch (error) {
            console.error('Error fetching posts:', error);
        }
    },
    async updatePost({ commit }, post) {
        try {
            await axios.put(`/api/posts/${post.id}`, {
                title: post.title,
                content: post.content
            });
            commit('updatePost', post);
        } catch (error) {
            console.error('Error updating post:', error);
        }
    },
    async deletePost({ commit }, postId) {
        try {
            await axios.delete(`/api/posts/${postId}`);
            commit('removePost', postId);
        } catch (error) {
            console.error('Error deleting post:', error);
        }
    }
};

const mutations = {
    setPosts: (state, posts) => (state.posts = posts),
    updatePost: (state, updatedPost) => {
        const index = state.posts.findIndex(post => post.id === updatedPost.id);
        if (index !== -1) {
            state.posts.splice(index, 1, updatedPost);
        }
    },
    removePost: (state, postId) => {
        state.posts = state.posts.filter(post => post.id !== postId);
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
