<template>
    <div>
        <header class="d-flex justify-content-between align-items-center py-3">
            <h1>CONVOHUB</h1>
            <div>
                <button v-if="!isAuthenticated" class="btn btn-secondary btnMargin" data-toggle="modal"
                    data-target="#signupModal">
                    Sign Up
                </button>
                <button class="btn btn-primary btnMargin" data-toggle="modal" data-target="#postModalcreate">
                    Create a Post
                </button>
                <button v-if="!isAuthenticated" class="btn btn-secondary btnMargin" data-toggle="modal"
                    data-target="#loginModal">
                    Log In
                </button>
                <button v-if="isAuthenticated" class="btn btn-danger btnMargin" @click="logout">
                    Log Out
                </button>
            </div>
        </header>

        <div class="container">
            <div class="main-content row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="conversations-tab" data-toggle="tab" href="#conversations"
                                role="tab" aria-controls="conversations" aria-selected="true">Conversations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="help-others-tab" data-toggle="tab" href="#help-others" role="tab"
                                aria-controls="help-others" aria-selected="false">Help others</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab"
                                aria-controls="categories" aria-selected="false">Categories</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="conversations" role="tabpanel"
                            aria-labelledby="conversations-tab">
                            <PostList ref="postList" />
                        </div>
                        <div class="tab-pane fade" id="help-others" role="tabpanel" aria-labelledby="help-others-tab">
                            <!-- Help others content -->
                        </div>
                        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                            <!-- Categories content -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <TopCreators />
                    <CreatedPost />
                </div>
            </div>
        </div>
        <button class="btn btn-primary feedback-button">Feedback</button>

        <!-- Modals -->
        <login-modal @login-success="handleLoginSuccess" />
        <register-modal @register-success="handleRegisterSuccess" />
        <post-modal @post-created="refreshPosts" />
    </div>
</template>

<script>
import axios from 'axios';
import LoginModal from './Login.vue';
import PostList from './PostList.vue';
import RegisterModal from './Register.vue';
import PostModal from './CreatePost.vue';
import TopCreators from './TopCreators.vue';
import CreatedPost from './CreatedPost.vue';

export default {
    components: {
        LoginModal,
        RegisterModal,
        PostModal,
        PostList,
        TopCreators,
        CreatedPost,
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
        },
        handleRegisterSuccess() {
        },
        refreshPosts() {
            if (this.$refs.postList && typeof this.$refs.postList.refreshPosts === 'function') {
                this.$refs.postList.refreshPosts();
            } else {
                console.error('refreshPosts method is not available');
            }
        }
    },
    mounted() {
        const token = localStorage.getItem('token');
        if (token) {
            this.isAuthenticated = true;
        }
    }
};
</script>

<style>
.btnMargin {
    margin-right: 3px;
}
</style>
