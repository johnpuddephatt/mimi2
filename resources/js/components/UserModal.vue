<template>
  <b-modal v-model="is_open" @close="$emit('input', false)" ref="userModal" :width="480" scroll="keep">
    <div class="card">
      <div class="card-content">
        <div class="media ">
          <div class="media-left">
            <figure class="image is-64x64">
              <img class="is-rounded" :src="user.photo" alt="Image">
            </figure>
          </div>
          <div class="media-content">
            <p class="title is-4">{{ user.first_name}} {{ user.last_name}}</p>
            <p class="subtitle is-6" v-if="$page.props.user.is_admin"><a :href="`mailto:${ user.email }`">{{ user.email }}</a></p>
            <p>{{ user.description }}</p>
            <br>
            <p><small>Joined <timeago :datetime="user.created_at"></timeago></small></p>
          </div>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: ['user', 'value'],
  data() {
    return {
      is_open: false
    };
  },
  watch: {
    value: function(value) {
      this.is_open = value
    }
  }
};
</script>


<style lang="scss"></style>
