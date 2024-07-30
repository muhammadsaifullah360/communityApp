<template>
    <div>
        <header class="d-flex justify-content-between align-items-center py-3">
            <h1>CONVOHUB</h1>
            <div class="gap">
                <button v-if="!isAuthenticated" class="btn btn-secondary" data-toggle="modal"
                    data-target="#signupModal">
                    Sign Up
                </button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#postModal">
                    Create a Post
                </button>
                <button v-if="!isAuthenticated" class="btn btn-secondary" data-toggle="modal" data-target="#loginModal">
                    Log In
                </button>
                <button v-if="isAuthenticated" class="btn btn-danger" @click="logout">
                    Log Out
                </button>
            </div>
        </header>

        <!-- Main content -->
        <div class="container">
            <router-view></router-view>
        </div>
        <button class="btn btn-primary feedback-button">Feedback</button>

        <!-- Modals -->
        <login-modal @login-success="handleLoginSuccess" />
        <register-modal @register-success="handleRegisterSuccess" />
        <post-modal />

    </div>
</template>

<script>
import axios from 'axios';
import LoginModal from './Login.vue';
import RegisterModal from './Register.vue';
import PostModal from './CreatePost.vue';

export default {
    components: {
        LoginModal,
        RegisterModal,
        PostModal,
    },
    data() {
        return {
            isAuthenticated: false,
        };
    },
    methods: {
        async logout() {
            try {
                const token = localStorage.getItem('token');
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                localStorage.removeItem('token');
                this.isAuthenticated = false;
            } catch (error) {
                console.error(error);
            }
        },
        handleLoginSuccess() {
            this.isAuthenticated = true;
            // Optionally, you can navigate to another page or show a success message
        },
        handleRegisterSuccess() {
            // You might want to log in the user automatically or show a success message
        }
    },
    mounted() {
        // Check if the user is authenticated on component mount
        const token = localStorage.getItem('token');
        if (token) {
            this.isAuthenticated = true;
        }
    }
};
</script>

<style>
/* Add your styles here */
</style>
