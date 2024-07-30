<template>
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Create Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createPost">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" v-model="title" id="title" placeholder="Enter Title" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" v-model="content" id="content" placeholder="Enter Content" required></textarea>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
// import $ from 'jquery'; // Make sure to import jQuery

export default {
    data() {
        return {
            title: '',
            content: '',
        };
    },
    methods: {
        async createPost() {
            try {
                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('content', this.content);

                const token = localStorage.getItem('token');
                const response = await axios.post('/api/posts', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        Authorization: `Bearer ${token}`,
                    },
                });

                this.$emit('post-created', response.data);
                this.resetForm();
                this.closeModal();
            } catch (error) {
                console.error('Error creating post:', error);
            }
        },
        resetForm() {
            this.title = '';
            this.content = '';
        },
        closeModal() {
            $('#postModal').modal('hide'); // Close the modal
            $('.modal-backdrop').remove(); // Remove the modal backdrop
        }
    }
};
</script>

<style scoped>
/* Add modal-specific styles here */
</style>
