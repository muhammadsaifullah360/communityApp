<template>
    <div class="top-creators">
        <h5>Top Creators</h5>
        <ul class="nav nav-tabs" id="topCreatorsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="week-tab" data-toggle="tab" href="#week" role="tab" aria-controls="week"
                    aria-selected="true">Week</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="alltime-tab" data-toggle="tab" href="#alltime" role="tab"
                    aria-controls="alltime" aria-selected="false">All time</a>
            </li>
        </ul>
        <div class="tab-content" id="topCreatorsTabContent">
            <div class="tab-pane fade show active" id="alltime" role="tabpanel" aria-labelledby="alltime-tab">
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center" v-for="creator in creators.allTime" :key="creator.id">
                        <img alt="avatar" class="creator-img">
                        {{ creator.name }} - {{ creator.posts_count }} posts
                    </li>
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
.top-creators{
    max-height: 500px;
    overflow-y: auto;
}
</style>
