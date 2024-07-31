<template>
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="login">
                        <div class="form-group">
                            <input type="email" class="form-control" v-model="email" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" v-model="password"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Log In</button>
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
            email: '',
            password: '',
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });
                localStorage.setItem('token', response.data.access_token);
                // Emit an event to fetch posts in the parent component
                this.$emit('login-success');
                this.$swal('Success', 'You have successfully logged in', 'success');
                $('#loginModal').modal('hide');
                $('.modal-backdrop').remove();
            } catch (error) {
                this.$swal('Error', 'Invalid email or password', 'error');
            }
        }

    },
};
</script>

<style>
/* Add your styles here */
</style>
