<template>
  <div class="user-list">

    <div class="notification is-danger" v-if="errorLoading">
      An error occured while trying to load users
    </div>

    <div v-else-if="isLoaded">

      <b-field v-if="users" class="mt-4 mb-4">
        <b-taginput
          v-model="newUsers"
          :open-on-focus="true"
          :data="filteredUsers"
          ellipsis
          expanded
          autocomplete
          :allow-new="false"
          placeholder="Add a person"
          field="first_name"
          @typing="getFilteredUsers"
        >
          <template slot-scope="props">
            {{ props.option.first_name }} {{ props.option.last_name }}
          </template>
        </b-taginput>
        <p class="control">
          <b-button @click="enrollSelected">Save</b-button>
        </p>
      </b-field>

      <div class="panel-block is-justify-between mb-4" v-for="user in enrolledUsers" :key="user.id">{{ user.first_name }} {{ user.last_name }} <button class="button is-small" @click="onUnenroll(user.id)">Unenroll</button></div>

    </div>

    <b-loading v-else :is-full-page="false" :active="!isLoaded"></b-loading>
  </div>

</template>

<script>
var platform = require('platform');

export default {
  props: ['course_id'],
  data() {
    return {
      isLoaded: false,
      isSaving: false,
      errorLoading: false,
      newUsers: [],
      errors: '',
      enrolledUsers: [],
      users: [],
      filteredUsers: this.users
    }
  },

  mounted() {
    axios({
        method: 'get',
        url: `/course/${this.course_id}/users`,
        timeout: 15000
      })
      .then(response => {
        this.enrolledUsers = response.data;
        this.isLoaded = true;
      })
      .catch(error => {
        this.errorLoading = true;
        axios.post('/log', {'error': `COURSE ENROLLED USERS GET ERROR, ${ platform.description }, ${ JSON.stringify(error) }`});
      }),

      axios({
          method: 'get',
          url: `/users`,
        })
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          this.errorLoading = true;
          axios.post('/log', {'error': `COURSE USERS GET ERROR, ${ platform.description }, ${ JSON.stringify(error) }`});
        })
  },

  watch: {
    isLoaded() {

    }
  },

  methods: {

    getFilteredUsers(text) {
        this.filteredUsers = this.users.filter((user) => {
            return (
              (user.first_name + ' ' + user.last_name)
                .toString()
                .toLowerCase()
                .indexOf(text.toLowerCase()) >= 0)
        })
    },

    enrollSelected() {
      axios({
        method: 'post',
        url: `/course/${this.course_id}/enroll`,
        data: this.newUsers.map(user => user.id),
        timeout: 90000
      })
      .then(response => {
        this.enrolledUsers = this.enrolledUsers.concat(this.newUsers);
        this.newUsers = [];
      })
      .catch(error => {
        this.isSaving = false;
        this.displayErrorNotification(error.response.data.message);
        axios.post('/log', {'error': `COURSE USER ENROLMENT ERROR\n${ platform.description }\n${ error.name }: ${ error.message }\nReply data: ${JSON.stringify(data)}\n\n`});
        this.errors = error.response.data.errors || '';
      });
    },

    onUnenroll(user_id) {

      axios({
          method: 'get',
          url: `/course/${this.course_id}/unenroll/user/${user_id}`
        })
        .then(response => {
          setTimeout(()=>{
            this.isSaving = false;
          }, 2000);
          this.enrolledUsers = this.enrolledUsers.filter(user => user.id != user_id);
        })
        .catch(error => {
          this.isSaving = false;
          this.displayErrorNotification(error.response.data.message);
          axios.post('/log', {'error': `COURSE USER DELETE ERROR\n${ platform.description }\n${ error.name }: ${ error.message }\nReply data: ${JSON.stringify(data)}\n\n`});
          this.errors = error.response.data.errors || '';
        });
    },

    displayErrorNotification(error) {
      this.$buefy.snackbar.open({
        message: `<b>Error:</b> ${error}`,
        type: 'is-danger',
        position: 'is-bottom',
        duration: 5000
      });
    }
  }
};
</script>

<style>

</style>
