<template>
    <div class="top-creators mt-2">
        <h5 class="d-flex post_heading">My Posts</h5>

        <div class="tab-content" id="">
            <div class="tab-pane fade show active" id="week" role="tabpanel" aria-labelledby="week-tab">
                <ul class="list-unstyled">
                    <li v-for="post in posts" :key="post.id"
                        class="d-flex align-items-center justify-content-between stye_list">
                        <p class="title_heading">{{ post.title }}</p>
                        <div class="d-flex gap-5 flex-direction-column">
                            <button @click="openEditModal(post)" class="common_btn">Edit</button>
                            <button @click="deletePost(post.id)" class="common_btn">Delete</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Post -->
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updatePost">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" v-model="title" id="title" placeholder="Enter Title"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" v-model="content" id="content" placeholder="Enter Content"
                                required></textarea>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const posts = reactive([]);
        const title = ref('');
        const content = ref('');
        const currentPostId = ref(null);

        const fetchPosts = async () => {
            try {
                const response = await axios.get('/api/posts');
                posts.push(...response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        };

        const openEditModal = (post) => {
            title.value = post.title;
            content.value = post.content;
            currentPostId.value = post.id;
            $('#postModal').modal('show');
        };

        const updatePost = async () => {
            try {
                await axios.put(`/api/posts/${currentPostId.value}`, {
                    title: title.value,
                    content: content.value
                });

                // Update the post in the local array
                const postIndex = posts.findIndex(post => post.id === currentPostId.value);
                if (postIndex !== -1) {
                    posts[postIndex] = { id: currentPostId.value, title: title.value, content: content.value };
                }

                closeModal();
            } catch (error) {
                console.error('Error updating post:', error);
            }
        };

        const deletePost = async (postId) => {
            try {
                await axios.delete(`/api/posts/${postId}`);
                const postIndex = posts.findIndex(post => post.id === postId);
                if (postIndex !== -1) {
                    posts.splice(postIndex, 1);
                }
            } catch (error) {
                console.log('Error deleting post:', error);
            }
        };

        const closeModal = () => {
            title.value = '';
            content.value = '';
            currentPostId.value = null;
            $('#postModal').modal('hide');
            $('.modal-backdrop').remove(); // Remove modal backdrop
        };

        onMounted(() => {
            fetchPosts();
        });

        return {
            posts,
            title,
            content,
            openEditModal,
            updatePost,
            deletePost,
            closeModal
        };
    }
};
</script>
<style>
/* Add your styles here */
.creator-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
}

.gap-5 {
    gap: 10px;
}

.title_heading {
    font-weight: 600;
    font-size: 16px;
}

.common_btn {
    background: transparent;
    border: none;
    color: #0644b8d2;
}

.post_heading {
    border-bottom: 1px solid red;
    align-items: center;
    width: 100%;
    display: flex;
    justify-content: center;
    padding-bottom: 15px;
}

.stye_list {
    border-bottom: 1px solid red;
    margin-bottom: 10px;
}

.top-creators {
    max-height: 500px;
    overflow-y: auto;
}
</style>
