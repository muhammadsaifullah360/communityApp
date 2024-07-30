<template>
       <!-- Post Modal -->
       <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" v-model="postTitle" id="title"
                                    placeholder="Enter Title" required>
                            </div>
                            <div class="form-group">
                                <label for="question">Question</label>
                                <input type="text" class="form-control" v-model="postQuestion" id="question"
                                    placeholder="Enter Question" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" @change="handleFileUpload" id="image">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" v-model="postDescription" id="description"
                                    placeholder="Enter Description" required></textarea>
                            </div>
                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

export default {
    data() {
        return {
            title: '',
            question: '',
            image: null,
            description: '',
        };
    },
    methods: {
        async createPost() {
            try {
                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('question', this.question);
                formData.append('description', this.description);
                if (this.image) {
                    formData.append('image', this.image);
                }

                const token = localStorage.getItem('token');
                const response = await axios.post('/api/posts', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        Authorization: `Bearer ${token}`,
                    },
                });

                console.log(response.data);
                this.$emit('post-created');
                this.resetForm();
            } catch (error) {
                console.error(error);
                // Handle error (e.g., show an error message)
            }
        },
        handleImageUpload(event) {
            this.image = event.target.files[0];
        },
        resetForm() {
            this.title = '';
            this.question = '';
            this.image = null;
            this.description = '';
        }
    }
};
</script>

<style scoped>
/* Add modal-specific styles here */
</style>
