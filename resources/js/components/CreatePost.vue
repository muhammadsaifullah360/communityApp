<template>
    <div class="modal fade" id="postModalcreate" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Create Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="handleCreatePost">
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
import { mapActions } from 'vuex';

export default {
    data() {
        return {
            title: '',
            content: '',
        };
    },
    methods: {
        ...mapActions(['createPost', 'fetchPosts']), // Make sure fetchPosts is included
        async handleCreatePost() {
            try {
                await this.createPost({ title: this.title, content: this.content });
                this.resetForm();
                this.closeModal();
                this.$emit('postCreated'); // Emit an event to notify parent or use a global event bus
            } catch (error) {
                console.error('Error creating post:', error);
            }
        },
        resetForm() {
            this.title = '';
            this.content = '';
        },
        closeModal() {
            $('#postModalcreate').modal('hide'); // Close the modal
            $('.modal-backdrop').remove(); // Remove the modal backdrop
        }
    }
};
</script>
