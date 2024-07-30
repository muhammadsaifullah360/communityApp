<template>
    <div class="top-creators">
      <h5>Top creators</h5>
      <ul class="nav nav-tabs" id="topCreatorsTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="week-tab" data-toggle="tab" href="#week" role="tab"
             aria-controls="week" aria-selected="true">Week</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="alltime-tab" data-toggle="tab" href="#alltime" role="tab"
             aria-controls="alltime" aria-selected="false">All time</a>
        </li>
      </ul>
      <div class="tab-content" id="topCreatorsTabContent">
        <div class="tab-pane fade show active" id="week" role="tabpanel" aria-labelledby="week-tab">
          <ul class="list-unstyled">
            <li class="d-flex align-items-center" v-for="creator in creators.week" :key="creator.id">
              <img :src="creator.image" alt="creator.name" class="creator-img">
              {{ creator.name }} - {{ creator.points }} points
            </li>
          </ul>
        </div>
        <div class="tab-pane fade" id="alltime" role="tabpanel" aria-labelledby="alltime-tab">
          <ul class="list-unstyled">
            <li class="d-flex align-items-center" v-for="creator in creators.allTime" :key="creator.id">
              <img :src="creator.image" alt="creator.name" class="creator-img">
              {{ creator.name }} - {{ creator.points }} points
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        creators: {
          week: [
            { id: 1, name: 'john.desborough', points: 299, image: './assets/images/image.jpg' },
            // Add more week creators here
          ],
          allTime: [
            { id: 1, name: 'john.desborough', points: 299, image: './assets/images/image.jpg' },
            // Add more all-time creators here
          ],
        },
      };
    },
  };
  </script>

  <style>
  /* Add your styles here */
  </style>
