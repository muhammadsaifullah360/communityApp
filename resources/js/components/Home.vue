<template>
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
              <PostList />
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
          <!-- <TopCreators /> -->
        </div>
      </div>
    </div>
  </template>

  <script>
  import PostList from './PostList.vue';
//   import TopCreators from './TopCreators.vue';

  export default {
    components: {
      PostList,
    //   TopCreators,
    },
  };
  </script>

  <style scoped>

  </style>
