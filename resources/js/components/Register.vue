<template>
    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="register">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="username" placeholder="Enter your username"
                                required>
                        </div>
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
            username: '',
            email: '',
            password: '',
        };
    },
    methods: {
        async register() {
            try {
                const response = await axios.post('/api/register', {
                    username: this.username,
                    email: this.email,
                    password: this.password,
                });
                console.log(response.data);
                this.$emit('register-success');
                $('#signupModal').modal('hide');
                $('.modal-backdrop').remove();
                this.$swal('Success', 'You have successfully resgitered', 'success');

            } catch (error) {
                console.error(error);


            }
        },
    },
};
</script>

<style>
/* Add your styles here */
</style>
;
}\;
