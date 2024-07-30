<template>
     <div class="top-creators mt-2">
                    <h3 class="d-flex post_heading">Created Post</h3>
                
                    <div class="tab-content" id="">
                        <div class="tab-pane fade show active" id="week" role="tabpanel" aria-labelledby="week-tab">
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center justify-content-between stye_list">
                                  <p class="title_heading"> Asked in Share your videoask testing purpose</p>
                                  <div class="d-flex gap-5 flex-direction-column">
                                    <button class="common_btn">Edit</button>
                                    <button class="common_btn">Delete</button>
                                  </div>
                                </li>
                                <li class="d-flex align-items-center justify-content-between">
                                  <p class="title_heading">hello world</p>
                                  <div class="d-flex gap-5 flex-direction-column">
                                    <button class="common_btn">Edit</button>
                                    <button class="common_btn">Delete</button>
                                  </div>
                                </li>
                                <li class="d-flex align-items-center justify-content-between">
                                  <p class="title_heading">hello world</p>
                                  <div class="d-flex gap-5 flex-direction-column">
                                    <button class="common_btn">Edit</button>
                                    <button class="common_btn">Delete</button>
                                  </div>
                                </li>
                             
                                <!-- Add more creators here -->
                            </ul>
                        </div>
                    </div>
                </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            creators: {
                week: [],
                allTime: []
            },
        };
    },
    async created() {
        await this.fetchCreators();
    },
    methods: {
        async fetchCreators() {
            try {
                const response = await axios.get('/api/top-creators');
                this.creators = response.data;
            } catch (error) {
                console.error('Error fetching creators:', error);
            }
        }
    },
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
.gap-5{
    gap: 10px;
}
.title_heading{
    font-weight: 600;
    font-size: 16px;
}
.common_btn{
    background: transparent;
    border: none;
    color: #000;
}
.post_heading{
    border-bottom: 1px solid red;
    align-items: center ;
    width: 100%;
    display: flex;
    justify-content: center;
    padding-bottom: 15px;
}
.stye_list{
    border-bottom: 1px solid red;
    margin-bottom: 10px;
}
.top-creators{
    max-height: 500px;
    overflow-y: auto;
}
</style>
