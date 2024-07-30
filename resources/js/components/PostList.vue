<template>
    <div>
      <div class="post" v-for="post in posts" :key="post.id">
        <h6>{{ post.author }} - Asked in {{ post.category }}</h6>
        <p><strong>{{ post.title }}</strong></p>
        <p>{{ post.description }}</p>
        <small>{{ post.timeAgo }}</small>
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        posts: [], // Initialize as an empty array
      };
    },
    async created() {
      await this.fetchPosts();
    },
    methods: {
      async fetchPosts() {
        try {
          const response = await axios.get('/api/posts');
          this.posts = response.data;
        } catch (error) {
          console.error('Error fetching posts:', error);
        }
      }
    }
  };
  </script>

  <style>
  /* Add your styles here */
  </style>
